<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    protected $validDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // Terima $day dari URL parameter
    public function index($day)
    {
        $day = ucfirst(strtolower($day)); // normalisasi input, e.g. "sunday" jadi "Sunday"

        if (!in_array($day, $this->validDays)) {
            return response()->json([
                'message' => 'Parameter "day" harus nama hari yang valid (Monday, Tuesday, ... Sunday)'
            ], 400);
        }

        $orderServiceUrl = env('ORDER_SERVICE_URL') . '/order';
        $response = Http::get($orderServiceUrl);

        if ($response->failed()) {
            return response()->json(['message' => 'Gagal mengambil data dari OrderService'], 500);
        }

        $orders = $response->json();

        if (!is_array($orders)) {
            return response()->json(['message' => 'Data dari OrderService tidak valid'], 500);
        }

        $filtered = collect($orders)->filter(function ($order) use ($day) {
            return isset($order['day']) && Str::lower($order['day']) === Str::lower($day);
        })->values();

        return response()->json([
            'day' => $day,
            'total_orders' => $filtered->count(),
            'orders' => $filtered,
        ]);
    }
}
