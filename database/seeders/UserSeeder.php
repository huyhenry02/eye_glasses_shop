<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/users.csv');
        $csvData = array_map('str_getcsv', file($path));
        $users = [];
        foreach ($csvData as $row) {
            $users[] = [
                'id' => (int)$row[0],
                'phone' => $row[1],
                'password' => Hash::make(1),
                'user_type' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);
    }
}
