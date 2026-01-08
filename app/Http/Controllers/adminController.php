<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/Dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function products()
    {
        $categories = ProductCategory::all();

        return Inertia::render('admin/Products', [
            'categories' => $categories,
        ]);
    }

    public function categories()
    {

        $categories = ProductCategory::all();

        return Inertia::render('admin/ManageCategories', [
            'categories' => $categories,
        ]);
    }

    public function stocks()
    {
        return Inertia::render('admin/ManageStocks');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        ProductCategory::create($validated);

        return redirect('/manage-categories');
    }

    /**
     * Display the specified resource.
     */
    public function showCategories()
    {

        $categories = ProductCategory::all();

        return Inertia::render('admin/Products', [
            'categories' => $categories,
        ]);
    }

    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $categories)
    {
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        ProductCategory::where('id', $categories->id)->update($validated);

        return redirect('/manage-categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $categories)
    {
        $categories->delete();

        return redirect('/manage-categories');
    }
}
