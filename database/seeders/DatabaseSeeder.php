<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
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


        $this->call([
            RoleSeeder::class,
        ]);

        $roles = Role::whereIn('name', ['admin', 'judge'])->get()->keyBy('name');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($roles['admin']->id, ['created_at' => now(), 'updated_at' => now()]);

        $judge1 = User::factory()->create([
            'name' => 'judge1',
            'email' => 'judge1@example.com',
        ]);
        $judge1->roles()->attach($roles['judge']->id, ['created_at' => now(), 'updated_at' => now()]);

    }
}
