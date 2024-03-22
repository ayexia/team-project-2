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
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function wishlist()
    {
        return view('wishlist');
    }

    public function addToWishlist($item, Request $request)
{
    if (auth()->check()) {
        $user = auth()->user();
        $product = Product::find($item);
        $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($wishlist) {
            return redirect()->back()->with('error', 'Product already in wishlist!');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return redirect()->back()->with('success', 'Product added to wishlist!');
    } else {
        $guestIdentifier = $request->session()->get('guest_identifier');
        if (!$guestIdentifier) {
            $guestIdentifier = Str::uuid()->toString();
            $request->session()->put('guest_identifier', $guestIdentifier);
        }
        $wishlistSession = session('wishlist', []);
        $product = Product::find($item);
        
        if (isset($wishlistSession[$item])) { 
            return redirect()->back()->with('error', 'Product already in wishlist!');
        } else {
            $wishlistSession[$item] = [
                'id' => $product->id,
                'image_url' => $product->image_url,
                'name' => $product->name,
                'price' => $product->price,
            ];
        }

        session(['wishlist' => $wishlistSession]); 
        session()->save();

        return redirect()->back()->with('success', 'Product added to wishlist!');
        }   
    }

    public function removeFromWishlist($item)
    {
        if (auth()->check()) {
            $product = Wishlist::find($item);
            if ($product) {
                $product->delete();
                }
                return redirect()->back()->with('success', 'Product removed from wishlist!');
        } else {
            $wishlistSession = session('wishlist', []);
            if (isset($wishlistSession[$item])) {
                unset($wishlistSession[$item]);
                session(['wishlist' => $wishlistSession]);
                session()->save();
                return redirect()->back()->with('success', 'Product removed from wishlist!');
            }
        }
        return redirect()->back();
    }
}