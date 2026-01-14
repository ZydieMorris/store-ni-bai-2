<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class cashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = ProductCategory::with('products')->get();
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        return Inertia::render('cashier/Dashboard', [
            'categories' => $categories,
            'cartItems' => $cartItems,

        ]);
    }

    public function addToCart(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_price' => 'required|numeric|min:0',
            'product_quantity' => 'required|numeric|min:1',

        ]);

        $cartItems = CartItem::where('user_id', auth()->id())->where('product_id', $request->product_id)->first();

        if ($cartItems) {
            $cartItems->increment('product_quantity', $request->product_quantity);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'product_quantity' => $request->product_quantity,
                'unit_price' => Product::find($request->product_id)->price,
            ]);

        }

        return redirect('/cashier/dashboard');

    }

    public function showCart() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function deleteCartItems(CartItem $cartItem)
    {
        $cartItem->delete();

        return redirect('/cashier/dashboard');
    }

    public function clearCart()
    {
        CartItem::where('user_id', auth()->id())->delete();

        return redirect('/cashier/dashboard');
    }

    public function payOrder(Request $request)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())->get();

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->unit_price * $item->product_quantity;
        });

        $changeAmount = $validated['amount_paid'] - $totalAmount;

        $order = Order::create([
            'user_id' => auth()->id(),
            'amount_paid' => $request->amount_paid,
            'total_amount' => $totalAmount,
            'change_amount' => $changeAmount,
            'status' => 'paid',
        ]);

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product) {
                $product->decrement('stock_available', $cartItem->product_quantity);
            }
        }

        CartItem::where('user_id', auth()->id())->delete();

        return redirect('/cashier/receipt'.'?order_id='.$order->id);
    }

    public function receipt(Request $request)
    {
        dd($request);

        $order = Order::findOrFail($request->input('order_id'));

        $cartItems = CartItem::with('product')->where('id', $order->id)->get();

        return Inertia::render('cashier/Receipt', [
            'order' => $order,
            'cartItems' => $cartItems,
        ]);
    }
}
