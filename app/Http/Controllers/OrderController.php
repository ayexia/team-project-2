<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        return view('checkout');
    }

    public function order()
    {
        return view('orders');
    }

    public function confirmOrder(Request $request)
{
    $address = $request->validate([
        'address' => 'required',
    ]);
    $guestEmail = $request->input('email');
    $existingUser = User::where('email', $guestEmail)->first();

    if ($existingUser) {
        return redirect()->back()->with('error', 'An account with this email already exists. Please log in or use a different email address.');
    }
    $cart = null;
    if (auth()->check()) {
        $user = auth()->user();
        $user_id = $user->id;
        $cart = Cart::where('user_id', auth()->user()->id)->first();
    } else {
        $guestUser = new User();
        $guestUser->name = $request->input('name'); 
        $guestUser->email = $request->input('email'); 
        $guestUser->password = bcrypt(Str::random(10));
        $guestUser->save();

        $user_id = $guestUser->id;
        $cartSession = session('cart', []);
    }
    $order = new Order();
    $order->address = $request->input('address');
    $order->order_date = now();
    $order->total_price = 0;
    $order->user_id = $user_id;
    $order->save();

    if ($cart) {
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->price = $product->price;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->save();

            $order->total_price += ($product->price * $cartItem->quantity);

            $product->quantity -= $cartItem->quantity;
            if ($product->quantity <= 0) {
                $product->available = 'no';
            }
            $product->save();

            $cartItem->delete();
        }
        $cart->delete();

    } elseif ($cartSession) {
        foreach ($cartSession as $item) {
            $product = Product::find($item['id']);

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];
            $orderItem->save();

            $order->total_price += ($product->price * $item['quantity']);

            $product->quantity -= $item['quantity'];
            if ($product->quantity <= 0) {
                $product->available = 'no';
            }
            $product->save();
        }
        session()->forget('cart');
    }
    $order->save();

    return redirect()->back()->with('success', 'Order placed!');
    }
}