<?php

namespace Flex\Core\Controllers;

use Flex\Core\Auth;
use Flex\Core\Controllers\BaseController;
use Flex\Core\Routing\View;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirectByUserRole();
            return;
        }

        $this->render(View::make('auth/login'));
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($username, $password)) {
            $this->redirectByUserRole();
            return;
        }

        $data = [
            'error' => 'Невалидно потребителско име или парола!',
            'old' => ['username' => $username]
        ];

        $this->render(View::make('auth/login', $data));
    }

    public function logout(): void
    {
        Auth::logout();
        View::redirect('/admin');
    }

    private function redirectByUserRole(): void
    {
        if (Auth::isAdmin()) {
            View::redirect('/admin/dashboard');
        } else {
            View::redirect('/');
        }
    }
}
