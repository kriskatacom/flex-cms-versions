<?php

namespace Flex\Core;

use Flex\Models\User;

class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = User::where('username', $username)->first();

        if ($user && password_verify($password, $user->password)) {
            self::login($user);
            return true;
        }

        return false;
    }

    public static function login(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['permissions'] = $user->getPermissions();
        $_SESSION['is_admin'] = $user->hasRole('admin');

        $user->update(['last_login' => date('Y-m-d H:i:s')]);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_role']);
        session_destroy();
    }

    public static function user(): ?User
    {
        if (self::check()) {
            return User::find($_SESSION['user_id']);
        }
        return null;
    }
}
