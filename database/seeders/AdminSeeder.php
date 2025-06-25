<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Admin Materialin';
        Admin::create([
            'name' => $name,
            'username' => strtolower(str_replace(' ', '', $name)),
            'email' => 'admin@materialin.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
