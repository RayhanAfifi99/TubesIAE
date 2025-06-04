<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
      $products = [
            [
                'name' => 'Kopi Arabika',
                'description' => 'Kopi berkualitas tinggi dengan cita rasa asam yang khas.',
                'price' => 35000
            ],
            [
                'name' => 'Teh Hijau',
                'description' => 'Teh alami yang menenangkan dan kaya antioksidan.',
                'price' => 25000
            ],
            [
                'name' => 'Susu Full Cream',
                'description' => 'Susu segar dengan rasa creamy dan kandungan kalsium tinggi.',
                'price' => 18000
            ],
            [
                'name' => 'Roti Tawar',
                'description' => 'Roti lembut yang cocok untuk sarapan atau camilan.',
                'price' => 15000
            ],
            [
                'name' => 'Minyak Goreng 1L',
                'description' => 'Minyak goreng sawit untuk keperluan memasak harian.',
                'price' => 28000
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'description' => 'Gula kristal putih murni untuk pemanis minuman dan masakan.',
                'price' => 17000
            ],
            [
                'name' => 'Mie Instan Ayam Bawang',
                'description' => 'Mie instan dengan rasa ayam bawang favorit keluarga.',
                'price' => 3500
            ],
            [
                'name' => 'Cokelat Batangan',
                'description' => 'Cokelat manis lezat untuk camilan kapan saja.',
                'price' => 12000
            ],
            [
                'name' => 'Air Mineral 600ml',
                'description' => 'Air minum dalam kemasan yang menyegarkan.',
                'price' => 4000
            ],
            [
                'name' => 'Beras Premium 5kg',
                'description' => 'Beras putih kualitas tinggi, pulen dan harum.',
                'price' => 72000
            ]
        ];

        $item = $this->faker->randomElement($products);

        return [
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => $item['price'],
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}