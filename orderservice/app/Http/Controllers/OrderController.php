<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Jobs\PublishOrderCreated;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function index()
    {
        // $order = Order::query()->get();

        // return response()->json($order);
        // return response()->json([
        //     'message' => 'This endpoint is disabled for public access.'
        // ], 403);
        $orders = Order::all();

        return response()->json($orders);
    }
    public function show($username, $productName)
    {
        $usernameEncoded = rawurlencode($username);
        $productNameEncoded = rawurlencode($productName);

        // Panggil UserService untuk dapatkan data user berdasarkan username
        $userResponse = Http::get(env('USER_SERVICE_URL') . "/users/by-name/{$usernameEncoded}");

        // return response()->json(['message' => env('USER_SERVICE_URL') . "users/by-name/{$usernameEncoded}"], 404);
        if ($userResponse->failed()) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user = $userResponse->json();

        // Panggil ProductService untuk dapatkan data produk berdasarkan productName
        $productResponse = Http::get(env('PRODUCT_SERVICE_URL') . "/products/by-name/{$productNameEncoded}");
        if ($productResponse->failed()) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product = $productResponse->json();

        // Gabungkan data dan kembalikan
        return response()->json([
            'user' => $user,
            'product' => $product,
            'message' => "Order info for user {$username} and product {$productName}"
        ]);
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string',
            'product_name' => 'required|string',
            'quantity' => 'required|integer',
        ]);

        $productResponse = Http::get(env('PRODUCT_SERVICE_URL') . "/products/by-name/" . rawurlencode($validated['product_name']));

        if ($productResponse->failed()) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product = $productResponse->json();
        $totalPrice = $product['price'] * $validated['quantity'];

        $urlDecreaseStock = env('PRODUCT_SERVICE_URL') . "/products/{$product['id']}/decrease-stock";

        $decreaseStockResponse = Http::post($urlDecreaseStock, [
            'quantity' => $validated['quantity']
        ]);

        if ($decreaseStockResponse->failed()) {
            return response()->json(['message' => 'Stock tidak mencukupi'], 500);
        }

        $day = Carbon::now()->format('l');

        $order = Order::create([
            'user_name'    => $validated['user_name'],
            'product_name' => $validated['product_name'],
            'quantity'     => $validated['quantity'],
            'total_price'  => $totalPrice,
            'day'          => $day
        ]);

        $payload = [
            'id' => $order->id,
            'user_name' => $order->user_name,
            'product_name' => $order->product_name,
            'quantity' => $order->quantity,
            'total_price' => $order->total_price,
            'day' => $order->day
        ];

        // ✅ Log internal (tidak muncul di response)
        Log::info('📦 Dispatching job to RabbitMQ', $payload);

        // ✅ Async job dengan delay
        dispatch(new PublishOrderCreated($payload))->delay(now()->addSeconds(10));

        // ✅ Minimalist response
        return response()->json([
            'message' => 'Order has been queued for processing.'
        ], 202); // 202 = async accepted

    }
}
