<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use FFI\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PSpell\Config;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        $products = Product::with('category')->orderBy('id')->get();
        return view('order.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'customer_name' => 'nullable|string',
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                $subtotal = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    // Lock row for update to prevent race conditions on stock
                    $product = Product::lockForUpdate()->findOrFail($item['id']);

                    if ($product->qty < $item['qty']) {
                        // Throwing an exception triggers auto-rollback inside DB::transaction
                        throw new \Exception("Stock untuk product '{$product->name}' tidak mencukupi.", 422);
                    }

                    $itemSubtotal = $product->price * $item['qty'];
                    $subtotal += $itemSubtotal;

                    $itemsData[] = [
                        'product' => $product,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $itemSubtotal
                    ];
                }

                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;
                $orderCode = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
                $paymentMethod = $request->payment_method ?? 'cash';

                $order = Order::create([
                    'order_code' => $orderCode,
                    'order_amount' => $total,
                    'order_change' => 0,
                    // 'status' => $paymentMethod === 'cash' ? 'success' : 'pending'
                ]);

                foreach ($itemsData as $data) {
                    OrderDetail::create([
                        'order_id'       => $order->id,
                        'product_id'     => $data['product']->id,
                        'order_qty'      => $data['qty'],
                        'order_price'    => $data['price'],
                        'order_subtotal' => $data['subtotal']
                    ]);

                    if ($paymentMethod === 'cash') {
                        $data['product']->decrement('qty', $data['qty']);
                    }
                }

                if ($paymentMethod === 'midtrans') {
                    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;

                    $params = [
                        "transaction_details" => [
                            "order_id" => $order->order_code,
                            "gross_amount" => (int) round($total)
                        ],
                        "customer_details" => [
                            'first_name' => $request->customer_name ?? 'Guest',
                        ],
                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    return [
                        'success' => true,
                        'payment_method' => 'midtrans',
                        'snap_token' => $snapToken,
                        'order_id' => $order->id,
                    ];
                }

                return [
                    'success' => true,
                    'payment_method' => 'cash',
                    'order_id' => $order->id
                ];
            });

            return response()->json($result, 200);
        } catch (\Throwable $th) {
            $status = $th->getCode() === 422 ? 422 : 500;

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], $status);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
