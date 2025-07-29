<?php

namespace Database\Seeders;

use App\Models\Merchant;
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
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Admin1'),
            'role' => 'admin',
        ]);
        $client = User::factory()->create([
            'name' => 'Test User',
            'email' => 'client1@client.com',
            'password' => Hash::make('Client1'),
            'role' => 'client',
        ]);
        Merchant::create([
            'user_id' => $client->id,
            'name' => 'Merchant Uno',
            'nit' => 123456789,
            'address' => 'Calle Falsa 123',
            'phone' => '7777777777',
            'account_username' => 'merchantuno',
            'account_password' => 'pass123',
            'image' => 'default.jpg',
            'rate_fixed' => 5.00,
            'rate_porcentage' => 2.50,
            'max_cash_register' => 2,
            'active_cash_register' => 0,
            'is_active' => true,
        ]);
        $this->command->info('User created');
    }
}
