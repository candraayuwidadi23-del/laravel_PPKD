<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $title = "Data Product";
        return view('product.index', compact('title', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create New Product";
        $categories = Category::get();
        return view('product.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        $data = [
            'name'=> $request->name,
            'category_id'=> $request->category_id,
            'price'=> $request->price,
            'description'=> $request->description
        ];
        //jika user mengupload foto 
        if ($request->hasFile('photo')){
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->to('product')->with('success', 'Create product success');
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
        $title = "Edit Product";
        $categories = Category::get();
        $edit = Product::findOrFail($id);
        return view('product.edit', compact('title', 'categories', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = [
            'name'=> $request->name,
            'category_id'=> $request->category_id,
            'price'=> $request->price,
            'description'=> $request->description
        ];
        if($request->hasFile('photo')){
            if($product->photo){
                Storage::disk('public')->delete($product->photo);
            
            }
            $data['photo'] = $request->file('photo')->store('product', 'public');
        }
        $product->update($data);
        return redirect()->to('product')->with('success', 'Update product success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
