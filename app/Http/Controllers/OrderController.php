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
        $orders = Order::all();
        return view('order.index', compact('orders'));
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

    public function creater()
    {
        $categories = Category::get();
        $products = Product::OrderBy('id')->get();
        return view('order.creater', compact('categories', 'products'));
    }
    public function notification(Request $request)
    {
        dd($request->all());
        $order = Order::where(
            'order_id',
            $request->order_id
        )->first();
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }
        if (
            $request->transaction_status === 'settlement' ||
            $request->transaction_status === 'capture'
        ) {
            if ($request->fraud_status === 'challenge') {
                $order->update([
                    'status' => 1
                ]);
            } else {
                $order->update([
                    'status' => 2
                ]);
            }
        } elseif ($request->transaction_status === 'pending') {
            $order->update([
                'status' => 1
            ]);
        } elseif (
            $request->transaction_status === 'deny' ||
            $request->transaction_status === 'expire' ||
            $request->transaction_status === 'cancel'
        ) {
            $order->update([
                'status' => 0
            ]);
        }
        return response()->json([
            'message' => 'Notification processed'
        ]);
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
                    'order_change' => $request->order_change,
                    'status' => $paymentMethod === 'cash' ? 1 : 0
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
                            "order_id" => $order->id,
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

    public function printRecipt(string $id)
    {
        $order = Order::with('orderDetails.product')->find($id);
        return view('order.print', compact('order'));
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
