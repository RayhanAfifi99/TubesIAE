<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Table name (opsional, jika nama model tidak sama persis dengan nama tabel)
    protected $table = 'orders';

    // Fillable fields (sudah benar)
    protected $fillable = [
        'user_name',
        'product_name',
        'quantity',
        'total_price',
        'day',
    ];

    // Casts untuk memastikan tipe data yang konsisten
    protected $casts = [
        'quantity'     => 'integer',
        'total_price'  => 'float',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];
}
