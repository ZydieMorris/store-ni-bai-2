<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->whereHas('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->withErrors('No items in cart.');
        }

        $totalAmount = $cartItems->sum(fn ($item) => $item->unit_price * $item->product_quantity);
        $changeAmount = $validated['amount_paid'] - $totalAmount;

        DB::transaction(function () use ($cartItems, $totalAmount, $changeAmount, $request, &$order) {

            // 3a️⃣ Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'amount_paid' => $request->amount_paid,
                'total_amount' => $totalAmount,
                'change_amount' => $changeAmount,
                'status' => 'paid',
            ]);

            // 3b️⃣ Create order items and decrement stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                if (! $product) {
                    continue;
                }

                // decrement stock
                $product->decrement('stock_available', $cartItem->product_quantity);

                // create snapshot in order_items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'quantity' => $cartItem->product_quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->unit_price * $cartItem->product_quantity,
                ]);
            }

            // 3c️⃣ Clear cart
            CartItem::where('user_id', auth()->id())->delete();
        });

        // 4️⃣ Redirect to receipt
        return redirect('/cashier/receipt'.'?order_id='.$order->id);
    }

    public function receipt(Request $request)
    {
        $order = Order::with('orderItems.product')
            ->findOrFail($request->query('order_id'));

        return Inertia::render('cashier/Receipt', [
            'order' => $order,
        ]);
    }
}
