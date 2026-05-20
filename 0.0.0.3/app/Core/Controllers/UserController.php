<?php

namespace Flex\Core\Controllers;

use Flex\Core\Routing\View;
use Flex\Models\User;
use Flex\Models\Role;
use Flex\Models\Permission;

class UserController extends BaseController
{
    public function index()
    {
        $currentTab = $_GET['tab'] ?? 'users';

        $users = User::with('roles')->orderBy('created_at', 'desc')->get();
        $roles = Role::orderBy('name', 'asc')->get();
        $permissions = Permission::orderBy('module', 'asc')->get();

        $config = $this->getTabConfig($currentTab);

        $this->render(View::make('admin/users/index', [
            'title' => $config['title'],
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'currentTab' => $currentTab,
            'primaryButton' => $config['button']
        ], 'admin'));
    }

    private function getTabConfig(string $tab): array
    {
        return match ($tab) {
            'roles' => [
                'title' => 'Роли и права',
                'button' => [
                    'label' => 'Нова роля',
                    'url' => '/admin/roles/create',
                    'icon' => 'fa-plus'
                ]
            ],
            'permissions' => [
                'title' => 'Системни разрешения',
                'button' => [
                    'label' => 'Ново разрешение',
                    'url' => '/admin/permission/create',
                    'icon' => 'fa-plus'
                ]
            ],
            default => [
                'title' => 'Потребители',
                'button' => [
                    'label' => 'Нов потребител',
                    'url' => '/admin/users/create',
                    'icon' => 'fa-plus'
                ]
            ],
        };
    }
}
