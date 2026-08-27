<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sidebar(){
        
        $menus = Menu::all();

        return view('app', compact('menus'));
    }
    public function index()
    {
        $menus = Menu::all();

        return view('menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:menus,id',
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'url' => 'nullable|string|max:88',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);
        Menu::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'icon' => $request->icon,
            'url' => $request->url,
            'sort_order' => $request->sort_order,
            'is_active' => $request->is_active
        ]);
        return redirect()->to('menu');
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
