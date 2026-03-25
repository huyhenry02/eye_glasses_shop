<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $path = database_path('seeders/data/products.csv');
        if (!file_exists($path)) {
            throw new RuntimeException("Không tìm thấy file products.csv");
        }
        $csvData = array_map('str_getcsv', file($path));
        $products = [];
        foreach ($csvData as $index => $row) {
            if ($index === 0 && $row[0] === 'id') {
                continue;
            }
            $products[] = [
                'id' => (int) $row[0],
                'category_id' => (int) $row[1],
                'code' => $row[2],
                'name' => $row[3],
                'slug' => $row[4],
                'description' => $row[5] ?? null,
                'brand' => $row[6] ?? null,
                'frame_material' => $row[7] ?? null,
                'lens_material' => $row[8] ?? null,
                'shape' => $row[9] ?? null,
                'rim_type' => $row[10] ?? null,
                'gender' => $row[11] ?? null,
                'frame_color' => $row[12] ?? null,
                'lens_color' => $row[13] ?? null,
                'colors' => $row[14] ?? null,
                'lens_width' => $this->toInt($row[15] ?? null),
                'bridge_width' => $this->toInt($row[16] ?? null),
                'temple_length' => $this->toInt($row[17] ?? null),
                'frame_width' => $this->toInt($row[18] ?? null),
                'price' => (int) $row[19],
                'discount_price' => $this->toInt($row[20] ?? null),
                'stock_quantity' => (int) $row[21],
                'image' => '/customer/images/product-03.avif',
                'image_detail_1' => '/customer/images/product-detail-03-01.avif',
                'image_detail_2' => '/customer/images/product-detail-03-02.avif',
                'image_detail_3' => '/customer/images/product-detail-03-03.avif',
                'is_active' => isset($row[22]) ? (int) $row[22] : 1,
                'is_featured' => isset($row[23]) ? (int) $row[23] : 0,

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('products')->insert($products);
    }

    /**
     * Convert string to int hoặc null
     */
    private function toInt($value): ?int
    {
        return ($value === '' || $value === null) ? null : (int) $value;
    }
}
