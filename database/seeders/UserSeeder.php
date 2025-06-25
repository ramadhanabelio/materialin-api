<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Gustavo Fring';
        User::create([
            'name' => $name,
            'username' => strtolower(str_replace(' ', '', $name)),
            'email' => 'gus@materialin.com',
            'password' => Hash::make('12345678'),
            'phone' => '000000000000',
            'address' => 'Jl. Pendidikan No. 123',
        ]);
    }
}
