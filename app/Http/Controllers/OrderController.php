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
            'door_number' => 'required|string|max:5',
            'street' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string|max:8|regex:/^[A-Z]{1,2}\d[A-Z\d]? \d[A-Z]{2}$/i',
    ]);
    $guestEmail = $request->input('email');
    $existingUser = User::where('email', $guestEmail)->first();
    if ($existingUser) {
        return redirect()->back()->with('error', 'An account with this email already exists. Please log in or use a different email address.');
    }
    $guestIdentifier = null;
    $cart = null;
    if (auth()->check()) {
        $user = auth()->user();
        $user_id = $user->id;
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $is_guest = false;
    } else {
        $guestIdentifier = $request->session()->get('guest_identifier');
        if (!$guestIdentifier) {
            $guestIdentifier = Str::uuid()->toString();
            $request->session()->put('guest_identifier', $guestIdentifier);
        }
        $guestUser = new User();
        $guestUser->name = $request->input('name'); 
        $guestUser->email = $request->input('email'); 
        $guestUser->password = bcrypt(Str::random(10));
        $guestUser->save();

        $user_id = $guestUser->id;
        $is_guest = true;
        $cartSession = session('cart', []);
    }
    $fullAddress = $request->door_number . ', ' . $request->street . ', ' . $request->city . ', ' . $request->postcode;
    $order = new Order();
    $order->address = $fullAddress;
    $order->order_date = now();
    $order->total_price = 0;
    $order->user_id = $user_id;
    $order->is_guest = $is_guest;
    $order->guest_identifier = $guestIdentifier;
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

    public function cancelOrder(Order $order)
    {
        if ($order->status === 'Pending' || $order->status === 'Processing') {
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem->product_id);
                $product->quantity += $orderItem->quantity;
                $product->save();
            }
            $order->status = 'Cancelled';
            $order->save();

            return redirect()->back()->with('success', 'Order has been cancelled successfully.');
        }
    }
}