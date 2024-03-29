<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Web24',
            'email' => 'debug@web24.com.pl',
            'password' => Hash::make('web24ppp'),
        ]);
    }
}
