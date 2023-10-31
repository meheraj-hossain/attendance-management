<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Admin\Employee::factory(50)->create();

        \App\Models\User::factory(5)->create([
            'status'   => '1',
            'role'     => '0',
            'password' => Hash::make('123456'),
        ]);

        \App\Models\User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'sup@ams.com',
            'status'   => '1',
            'role'     => '1',
            'password' => Hash::make('kk-50'),
        ]);
    }
}
