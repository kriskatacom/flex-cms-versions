<?php

namespace Flex\Core\Controllers;

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Flex\Core\Controllers\BaseController;
use Flex\Core\Routing\View;
use ZipArchive;

class UpdateController extends BaseController
{
    private string $githubRepo = 'kriskatacom/flex-cms-versions';
    private string $githubToken;

    public function __construct()
    {
        $this->githubToken = $_ENV['GITHUB_UPDATE_TOKEN'] ?? getenv('GITHUB_UPDATE_TOKEN') ?: '';
    }

    public function index()
    {
        $currentConfig = require dirname(__DIR__) . '/version.php';
        $currentVersion = $currentConfig['version'];

        $url = "https://api.github.com/repos/{$this->githubRepo}/contents/";
        $response = $this->makeGithubRequest($url);

        $availableVersions = [];
        $latestVersion = $currentVersion;
        $hasUpdate = false;
        $changelog = [];
        $downloadUrl = null;

        if ($response) {
            $items = json_decode($response, true);

            if (is_array($items)) {
                if (isset($items['message'])) {
                    $changelog = ['Грешка от GitHub: ' . $items['message']];
                } else {
                    foreach ($items as $item) {
                        if (!is_array($item))
                            continue;

                        if (isset($item['type']) && $item['type'] === 'dir' && preg_match('/^\d+(\.\d+)+$/', $item['name'])) {
                            $versionName = $item['name'];

                            if (version_compare($versionName, $latestVersion, '>')) {
                                $latestVersion = $versionName;
                                $hasUpdate = true;
                            }

                            $versionInfo = $this->getVersionDetails($versionName);

                            $availableVersions[$versionName] = [
                                'version' => $versionName,
                                'release_date' => $versionInfo['release_date'] ?? 'Неизвестна',
                                'description' => $versionInfo['description'] ?? 'Няма предоставено описание.',
                            ];
                        }
                    }
                }
            }
        }

        uksort($availableVersions, 'version_compare');
        $availableVersions = array_reverse($availableVersions);

        if ($hasUpdate && isset($availableVersions[$latestVersion])) {
            $changelog = is_array($availableVersions[$latestVersion]['description'])
                ? $availableVersions[$latestVersion]['description']
                : [$availableVersions[$latestVersion]['description']];

            $downloadUrl = "https://github.com/{$this->githubRepo}/archive/refs/heads/main.zip";
        }

        $updateData = [
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'has_update' => $hasUpdate,
            'changelog' => $changelog,
            'download_url' => $downloadUrl,
            'all_versions' => $availableVersions
        ];

        return $this->render(View::make('admin/update/index', [
            'title' => 'Обновяване на системата',
            'update' => $updateData
        ], 'admin'));
    }

    private function runPhinxMigrations(string $rootPath)
    {
        $phinxConfigPath = $rootPath . 'phinx.php';

        if (!file_exists($phinxConfigPath)) {
            error_log("Phinx грешка: Не е намерен phinx.php в " . $rootPath);
            return;
        }

        try {
            // 1. Вземаме съществуващата PDO връзка директно от Eloquent Capsule,
            // която вече е конфигурирана и отворена в твоя index.php!
            $pdo = \Illuminate\Database\Capsule\Manager::connection()->getPdo();

            // 2. Зареждаме оригиналния масив с настройки от phinx.php
            $configArray = require $phinxConfigPath;
            $envName = $configArray['environments']['default_environment'] ?? 'development';

            // 3. Инжектираме готовото PDO във фейк среда, за да прескочим CLI защитите на Phinx
            $configArray['environments'][$envName]['connection'] = $pdo;

            // 4. Подаваме пътищата към миграциите правилно, като заменяме %%PHINX_CONFIG_DIR%% с реалния $rootPath
            if (isset($configArray['paths']['migrations'])) {
                $configArray['paths']['migrations'] = str_replace('%%PHINX_CONFIG_DIR%%', rtrim($rootPath, '/'), $configArray['paths']['migrations']);
            }
            if (isset($configArray['paths']['seeds'])) {
                $configArray['paths']['seeds'] = str_replace('%%PHINX_CONFIG_DIR%%', rtrim($rootPath, '/'), $configArray['paths']['seeds']);
            }

            // 5. Инициализираме Phinx мениджъра
            $config = new \Phinx\Config\Config($configArray);
            $input = new \Symfony\Component\Console\Input\StringInput('');
            $output = new \Symfony\Component\Console\Output\NullOutput();

            $manager = new \Phinx\Migration\Manager($config, $input, $output);

            // 6. Изпълняваме миграцията в същия процес
            ob_start();
            $manager->migrate($envName);
            ob_get_clean();

            error_log("Phinx Успех: Миграциите преминаха успешно през Eloquent PDO!");

        } catch (\Exception $e) {
            error_log("Критична грешка при Phinx през Eloquent: " . $e->getMessage());
        }
    }

    private function getVersionDetails(string $version): array
    {
        $rawUrl = "https://raw.githubusercontent.com/{$this->githubRepo}/main/{$version}/app/Core/version.php";
        $response = $this->makeGithubRequest($rawUrl);

        if ($response) {
            preg_match("/'release_date'\s*=>\s*['\"]([^'\"]+)['\"]/", $response, $dateMatch);
            preg_match("/'description'\s*=>\s*['\"]([^'\"]+)['\"]/s", $response, $descMatch);

            return [
                'release_date' => $dateMatch[1] ?? 'Неизвестна дата',
                'description' => $descMatch[1] ?? 'Няма предоставено описание.'
            ];
        }

        return [];
    }

    public function update()
    {
        $url = "https://api.github.com/repos/{$this->githubRepo}/contents/";
        $response = $this->makeGithubRequest($url);

        if (!$response) {
            return $this->jsonResponse(false, 'Няма връзка с GitHub за изтегляне на обновяването.');
        }

        $items = json_decode($response, true);
        $currentConfig = require dirname(__DIR__) . '/version.php';
        $currentVersion = $currentConfig['version'];
        $latestVersion = $currentVersion;
        $hasUpdate = false;

        if (is_array($items) && !isset($items['message'])) {
            foreach ($items as $item) {
                if (is_array($item) && $item['type'] === 'dir' && preg_match('/^\d+(\.\d+)+$/', $item['name'])) {
                    if (version_compare($item['name'], $latestVersion, '>')) {
                        $latestVersion = $item['name'];
                        $hasUpdate = true;
                    }
                }
            }
        }

        if (!$hasUpdate) {
            return $this->jsonResponse(false, 'Системата вече е актуализирана до последната версия.');
        }

        $zipFile = __DIR__ . '/../../../storage/tmp/update.zip';
        $extractPath = __DIR__ . '/../../../storage/tmp/extracted/';
        $rootPath = __DIR__ . '/../../../';

        if (!is_dir(dirname($zipFile))) {
            mkdir(dirname($zipFile), 0755, true);
        }

        $downloadUrl = "https://github.com/{$this->githubRepo}/archive/refs/heads/main.zip";

        $ch = curl_init($downloadUrl);
        $fp = fopen($zipFile, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            $sourceVersionFolder = $extractPath . 'flex-cms-versions-main/' . $latestVersion;

            if (!is_dir($sourceVersionFolder)) {
                $this->cleanUp($extractPath, $zipFile);
                return $this->jsonResponse(false, "Грешка: Папката {$latestVersion} не съществува в архива.");
            }

            $excludeList = [
                'plugins',
                'storage/uploads',
                'config.php',
                '.env',
            ];

            $this->smartCopy($sourceVersionFolder, $rootPath, $excludeList);
            $this->cleanUp($extractPath, $zipFile);

            $this->runPhinxMigrations($rootPath);

            return $this->jsonResponse(true, "Flex CMS беше обновена успешно до версия v{$latestVersion}!");
        }

        $this->cleanUp($extractPath, $zipFile);
        return $this->jsonResponse(false, 'Проблем при отварянето на инсталационния ZIP пакет.');
    }

    private function smartCopy(string $src, string $dst, array $exclude = [], string $currentRelativePath = '')
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..')
                continue;

            $relativePath = $currentRelativePath ? $currentRelativePath . '/' . $file : $file;

            if (in_array($relativePath, $exclude))
                continue;

            if (is_dir($src . '/' . $file)) {
                $this->smartCopy($src . '/' . $file, $dst . '/' . $file, $exclude, $relativePath);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
        closedir($dir);
    }

    private function cleanUp(string $dir, string $zip)
    {
        if (file_exists($zip))
            unlink($zip);
        $this->deleteDir($dir);
    }

    private function deleteDir(string $dirPath)
    {
        if (!is_dir($dirPath))
            return;
        $files = array_diff(scandir($dirPath), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dirPath/$file")) ? $this->deleteDir("$dirPath/$file") : unlink("$dirPath/$file");
        }
        return rmdir($dirPath);
    }

    private function makeGithubRequest(string $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'FlexCMS-Updater');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $headers = [
            'Authorization: token ' . $this->githubToken,
            'Accept: application/vnd.github.v3+json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        return $response;
    }

    private function jsonResponse(bool $success, string $message)
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }
}
