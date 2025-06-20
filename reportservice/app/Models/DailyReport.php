<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'total_orders', 'orders'];

    protected $casts = [
        'orders' => 'array',
    ];
}