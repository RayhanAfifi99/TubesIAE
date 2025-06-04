<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{

    public function index(){
        $order= Order::query()->get();

        return response()->json($order);
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

 // Panggil ProductService untuk dapatkan data produk berdasarkan productName


 $url = env('PRODUCT_SERVICE_URL') . "/products/by-name/" . rawurlencode($validated['product_name']);
        $productResponse = Http::get($url);
//  return response()->json([
//     'b' => $url,
//     'a' => $productResponse->failed(),
// ], 404);
        if ($productResponse->failed()) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product = $productResponse->json();

        $totalPrice = $product['price'];


          // decrease stock here

        // '/products/{id}/decrease-stock'
        $urlDecreaseStock = env('PRODUCT_SERVICE_URL') . "/products/{$product['id']}";
        $decreaseStockResponse = Http::post($urlDecreaseStock, [
            'quantity'=>$validated['quantity']
        ]);

        if ($productResponse->failed()) {
            return response()->json(['message' => 'Stock tidak mencukupi'], 500);
        }

        $order = Order::create([
            'user_name'=>$validated['user_name'],
            'product_name' => $validated['product_name'],
            'quantity'=> $validated['quantity'],
            'total_price' => $totalPrice
        ]);



        return response()->json($order, 201);
    }
}
