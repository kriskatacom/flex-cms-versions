<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@flex-cms.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->table('users')->insert($data)->saveData();
    }
}
