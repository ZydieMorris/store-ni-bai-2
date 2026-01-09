<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

    public function storeProducts(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload
        $image = $request->file('image');
        $filename = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images'), $filename);

        // Save product
        Product::create([
            'product_name' => $validated['product_name'],
            'price' => $validated['price'],
            'product_category_id' => $validated['category_id'],
            'image' => 'images/'.$filename,
        ]);

        return redirect('/products');
    }

    public function showProducts()
    {

        $products = Product::all();

        return Inertia::render('admin/ManageStocks', [
            'products' => $products,
        ]);
    }

   public function manageStocks(Request $request)
{
    $categoryId = $request->query('category_id');

    $products = Product::where('product_category_id', $categoryId)->get();

    return Inertia::render('admin/ManageStocks', [
        'products' => $products,
        'categoryId' => $categoryId,
    ]);
}


    /**
     * Display the specified resource.
     */
    public function showCategories()
    {

        $categories = ProductCategory::all();
        $products = Product::all();

        return Inertia::render('admin/Products', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

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

   public function addStocks(Request $request, Product $product)
{
    $validated = $request->validate([
        'stock' => 'required|integer|min:1',
    ]);

    // Increment stock
    $product->increment('stock', $validated['stock']);

    return redirect('/manage-stocks')->with('success', 'Stock added successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $categories)
    {
        $categories->delete();

        return redirect('/manage-categories');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect('/manage-stocks');
    }
}
