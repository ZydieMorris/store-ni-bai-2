<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

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

 public function payOrder(Request $request)
{
   $validated = $request->validate([
    'amount_paid' => 'required|numeric|min:0',
]);

$cartItems = CartItem::where('user_id', auth()->id())
    ->with('product')
    ->get();

if ($cartItems->isEmpty()) {
    return back()->withErrors('Cart is empty');
}

// Calculate total
$totalAmount = $cartItems->sum(fn ($item) =>
    $item->unit_price * $item->product_quantity
);

if ($validated['amount_paid'] < $totalAmount) {
    return back()->withErrors('Insufficient payment');
}

$change = $validated['amount_paid'] - $totalAmount;

DB::transaction(function () use ($cartItems, $validated, $totalAmount, $change) {

    // 1️⃣ Create ONE order
    $order = Order::create([
        'user_id' => auth()->id(),
        'amount_paid' => $validated['amount_paid'],
        'total_amount' => $totalAmount,
        'change_amount' => $change,
        'status' => 'paid',
    ]);

    // 2️⃣ Create order items + deduct stock
    foreach ($cartItems as $item) {

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'product_name' => $item->product->product_name,
            'quantity' => $item->product_quantity,
            'unit_price' => $item->unit_price,
            'total_price' => $item->unit_price * $item->product_quantity,
        ]);

        $item->product->decrement(
            'stock_available',
            $item->product_quantity
        );
    }

    // 3️⃣ Clear cart
    CartItem::where('user_id', auth()->id())->delete();
});

return redirect('/cashier/dashboard')
    ->with('success', 'Order paid successfully');

}

}