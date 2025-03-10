<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::whereIn('name', ['admin', 'judge', 'contestant'])->get()->keyBy('name');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@uniwide.com',
            'password' => Hash::make('passwordforuniwide'),
        ]);
        $admin->roles()->attach($roles['admin']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Carig
        $carig = User::create([
            'name' => 'Carig',
            'email' => 'carig@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $carig->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Gonzaga
        $gonzaga = User::create([
            'name' => 'Gonzaga',
            'email' => 'gonzaga@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $gonzaga->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Lallo
        $lallo = User::create([
            'name' => 'Lallo',
            'email' => 'lallo@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $lallo->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Lasam
        $lasam = User::create([
            'name' => 'Lasam',
            'email' => 'lasam@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $lasam->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Piat
        $piat = User::create([
            'name' => 'Piat',
            'email' => 'piat@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $piat->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Sanchez Mira
        $sanchezMira = User::create([
            'name' => 'Sanchez Mira',
            'email' => 'sanchezmira@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $sanchezMira->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

        // Solana
        $solana = User::create([
            'name' => 'Solana',
            'email' => 'solana@uniwide.com',
            'password' => Hash::make('uniwide'),
        ]);
        $solana->roles()->attach($roles['contestant']->id, ['created_at' => now(), 'updated_at' => now()]);

    }
}
