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

    public function confirmOrder(Request $request){

        $address = $request->validate([
            'address' => 'required',
        ]);

        $user = auth()->user();
        $order = Order::where('user_id', optional($user)->id)->first();

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->address = $request->input('address');
        $order->order_date = now();
        $order->total_price = 0;
        $order->save();
        
        $cart = Cart::where('user_id', optional($user)->id)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        if ($cartItems !== null) {
        foreach($cartItems as $cartItem){
            
        $product = Product::find($cartItem->product_id);

        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $product->id;
        $orderItem->price = $product->price;
        $orderItem->quantity = $cartItem->quantity;
        $orderItem->save();

        $order->total_price += ($product->price*$cartItem->quantity);
        $product->quantity -= $cartItem->quantity;
        $product->save();
        if ($product->quantity <= 0) {
            $product->available = 'no';
            $product->save();
        }

        $cartItem->delete();
        $cart->delete();
        }
        
    }
        
        $order->save();

        return redirect()->back()->with('success', 'Order placed!');
    }
}
