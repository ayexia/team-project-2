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

class CartController extends Controller
{
    public function cart()
    {
        $categories = Category::all();
        return view('cart', compact('categories'));
    }

    public function addToCart($item, Request $request)
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
    $size = $request->input('size'); 
    if ($cartItem) {
        $cartItem->quantity += request('quantity');
        $cartItem->size = $size; 
        $cartItem->save();

    } else {
        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => request('quantity'),
            'price' => $product->price,
            'size' => $size, 
        ]);
        $cartItem->save();
    }

    return redirect()->back()->with('success', 'Product added to cart!');
        } else {
            $guestIdentifier = $request->session()->get('guest_identifier');
            if (!$guestIdentifier) {
                $guestIdentifier = Str::uuid()->toString();
                $request->session()->put('guest_identifier', $guestIdentifier);
            }
            $cartSession = session('cart', []);
            $product = Product::find($item);
            $size = $request->input('size');
            if (isset($cartSession[$item])) {
                $cartSession[$item]['quantity'] += request('quantity');
                $cartSession[$item]['size'] = $size;
            } else {
                $cartSession[$item] = [
                    'id' => $product->id,
                    'image_url' => $product->image_url,
                    'name' => $product->name,
                    'quantity' => request('quantity'),
                    'price' => $product->price,
                    'max_quantity' => $product->quantity,
                    'size' => $size,
                ];
            }
            
            session(['cart' => $cartSession]);
            session()->save();
            
            return redirect()->back()->with('success', 'Product added to cart!');
        }
    }

    public function removeFromCart($item)
    {
        if (auth()->check()) {
            $product = CartItem::find($item);
            if ($product) {
                $product->delete();
                $cart = Cart::find($product->cart_id);
                if ($cart) {
                    $cartItems = CartItem::where('cart_id', $cart->id)->get();
                    if ($cartItems->isEmpty()) {
                        $cart->delete();
                    }
                }
                return redirect()->back()->with('success', 'Product removed from cart!');
            }
        } else {
            $cartSession = session('cart', []);
            if (isset($cartSession[$item])) {
                unset($cartSession[$item]);
                session(['cart' => $cartSession]);
                session()->save();
                return redirect()->back()->with('success', 'Product removed from cart!');
        }
    }
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
    } else {
        $cartSession = session('cart', []);
        $item = $request->input('cart_item_id');

        if (isset($cartSession[$item])) {
            $quantity = $request->input('quantity');

            if ($quantity > 0) {
                $cartSession[$item]['quantity'] = $quantity;
                session(['cart' => $cartSession]);
                session()->save();
                return redirect()->back()->with('success', 'Cart updated successfully!');
            } else {
                unset($cartSession[$item]);
                session(['cart' => $cartSession]);
                session()->save();
                return redirect()->back()->with('success', 'Product removed from cart!');
                }
            }
        }
    }
}
