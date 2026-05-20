<?php

namespace Flex\Core\UI;

class Page
{
    public static function header(string $title, string|null $backUrl = null, string|null $subtitle = null): void
    {
        ?>
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <?php if ($backUrl): ?>
                        <a href="<?= $backUrl ?>"
                            class="flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-500">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    <?php endif; ?>
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white"><?= $title ?></h1>
                </div>
                <?php if ($subtitle): ?>
                    <p class="mt-1 text-slate-500 dark:text-slate-400 ml-12"><?= $subtitle ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function alert(string $type, string $title, string $description, array $options = []): void
    {
        $id = $options['id'] ?? 'alert-' . bin2hex(random_bytes(4));
        $limit = $options['limit'] ?? 0;
        $icon = $options['icon'] ?? null;

        $styles = [
            'success' => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'border' => 'border-emerald-200 dark:border-emerald-500/20', 'text' => 'text-emerald-800 dark:text-emerald-400', 'icon_bg' => 'bg-emerald-100 dark:bg-emerald-500/20', 'default_icon' => 'fa-circle-check'],
            'error' => ['bg' => 'bg-rose-50 dark:bg-rose-500/10', 'border' => 'border-rose-200 dark:border-rose-500/20', 'text' => 'text-rose-800 dark:text-rose-400', 'icon_bg' => 'bg-rose-100 dark:bg-rose-500/20', 'default_icon' => 'fa-circle-xmark'],
            'warning' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10', 'border' => 'border-amber-200 dark:border-amber-500/20', 'text' => 'text-amber-800 dark:text-amber-400', 'icon_bg' => 'bg-amber-100 dark:bg-amber-500/20', 'default_icon' => 'fa-triangle-exclamation'],
            'info' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10', 'border' => 'border-blue-200 dark:border-blue-500/20', 'text' => 'text-blue-800 dark:text-blue-400', 'icon_bg' => 'bg-blue-100 dark:bg-blue-500/20', 'default_icon' => 'fa-circle-info'],
        ];

        $s = $styles[$type] ?? $styles['info'];
        $displayIcon = $icon ?? $s['default_icon'];

        ?>
        <div
            x-data="alertComponent('<?= $id ?>', <?= $limit ?>)" 
            x-show="visible" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="flex gap-4 p-4 mb-5 rounded-md border <?= $s['bg'] ?> <?= $s['border'] ?>">

            <div class="shrink-0">
                <div class="w-10 h-10 rounded-md flex items-center justify-center <?= $s['icon_bg'] ?> <?= $s['text'] ?>">
                    <i class="fa-solid <?= $displayIcon ?> text-lg"></i>
                </div>
            </div>

            <div class="flex-1">
                <h4 class="text-lg font-semibold <?= $s['text'] ?> leading-tight mb-1"><?= $title ?></h4>
                <p class="opacity-90 <?= $s['text'] ?> leading-relaxed"><?= $description ?></p>
            </div>

            <button type="button" @click="visible = false" class="shrink-0 h-6 w-6 flex items-center justify-center rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors <?= $s['text'] ?>">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <?php
    }
}