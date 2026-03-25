<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/categories.csv');
        $csvData = array_map('str_getcsv', file($path));

        $categories = [];

        foreach ($csvData as $index => $row) {
            if ($index === 0 && $row[0] === 'id') {
                continue;
            }
            $categories[] = [
                'id' => (int)$row[0],
                'code' => $row[1],
                'name' => $row[2],
                'slug' => $row[3],
                'description' => $row[4],
                'is_active' => isset($row[5]) ? (int)$row[5] : 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('categories')->insert($categories);
    }
}
