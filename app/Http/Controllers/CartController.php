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

class CartController extends Controller
{
    public function cart()
    {
        return view('cart');
    }

    public function addToCart($item)
    {
    if (auth()->check()) {
    $user = auth()->user();
    $product=Product::find($item);
    $cart = Cart::where('user_id', auth()->user()->id)->first();
    $cartItem = CartItem::where('cart_id', optional($cart)->id)
    ->where('product_id', $product->id)
    ->first();
    
    if (!$cart) {
        $cart = new Cart();
        $cart->user_id = auth()->user()->id;
        $cart->save();
    }
    if ($cartItem) {
        $cartItem->quantity += request('quantity');
        $cartItem->save();

    } else {
        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => request('quantity'),
            'price' => $product->price,
        ]);
        $cartItem->save();
    }

    return redirect()->back()->with('success', 'Product added to cart!');
        } else {
            $cartSession = session('cart', []);
        
            if (isset($cartSession[$item])) {
                $cartSession[$item]['quantity'] += request('quantity');
            } else {
                $cartSession[$item] = [
                    'product_id' => $item,
                    'quantity' => request('quantity'),
                ];
            }
            
            session(['cart' => $cartSession]);
            session()->save();
            
            return redirect()->back()->with('success', 'Product added to cart!');
        }
    }

    public function removeFromCart($item){

        $product=CartItem::find($item);
        $product->delete();
        $cart = Cart::find($product->cart_id);

        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
                if ($cartItems->isEmpty()) {
                $cart->delete();
            }
        }
    
        return redirect()->back()->with('success', 'Product deleted from cart!');
    }

    public function updateCart(Request $request)
{
    $cartItem = CartItem::find($request->input('cart_item_id'));

    if ($cartItem) {
        $quantity = $request->input('quantity');

        if ($quantity > 0) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        } else {
            $cartItem->delete();
            $cart = Cart::find($cartItem->cart_id);
            if ($cart->cartItems->isEmpty()) {
                $cart->delete();
            }
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    return redirect()->back()->with('error', 'Failed to update cart.');
    }
}
