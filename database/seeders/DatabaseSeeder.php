<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Sound;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(25)->create();
        
        User::factory()->create([
            'name' => 'Matteo Demicheli',
            'email' => 'matteo@matteodem.ch',
            'password' => Hash::make('testtest')
        ]);

        Sound::factory(100)->create();
    }
}
