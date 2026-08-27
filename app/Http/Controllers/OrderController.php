<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Buat Validasi
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            // 'customer_name'
        ]);
        try {
            DB::transaction(function () use ($request) {
                $subtotal = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['id']);
                    if ($product->qty < $item['qty']) {
                        return response()->json([
                            'message' => "Tidak ada stock"
                        ], 400);
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
                $tax           = $subtotal * 0.1;
                $total         = $subtotal + $tax;
                $orderCode     = 'ORD' . date('Ymd') . '-' . rand(1000, 9999);
                $paymentMethod = $request->payment_method ?? 'cash';

                $order = order::create([
                    'order_code' => $orderCode,
                    'order_amount' => $total,
                    'order_change' => 0,
                    // 'order_status' => $paymentMethod === 'cash' ? 'success' : 'pending'
                ]);

                foreach ($itemsData as $data) {
                    OrderDetail::create([
                        'order_id'     => $order->id,
                        'product_id'     => $data['product']->id,
                        'order_qty'      => $data['qty'],
                        'order_price'    => $data['price'],
                        'order_subtotal' => $data['subtotal']
                    ]);
                    if ($paymentMethod === 'cash') {
                        $data['product']->decrement('qty', $data['qty']);
                    }
                }
                return response()->json([
                    'success' => true,
                    'payment_method' => 'cash',
                    'order_id' => $order->id
                ]);
            });
        } catch (\Exception $th) {
            //kalau gagal
            return response()->json([
                'message' => 'Gagal menyimpan transaksi' . $th->getMessage()
            ], 500);
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
