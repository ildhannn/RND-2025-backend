<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'alamat' => 'Sukawarna',
            'status' => 1,
            'kewenangan_id' => 1,
            'password' => Hash::make('123456')
        ]);
    }
}
