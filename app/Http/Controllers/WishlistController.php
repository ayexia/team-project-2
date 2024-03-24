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
        $categories = Category::all();
        return view('wishlist', compact('categories'));
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
        }   
    }

    public function removeFromWishlist($item)
{
    if (auth()->check()) {
        $user = auth()->user();
        $wishlistItem = Wishlist::find($item);
        
        if ($wishlistItem && $wishlistItem->user_id === $user->id) {
            $wishlistItem->delete();
            if ($user->wishlist->isEmpty()) {
                $user->wishlist()->delete(); 
             }
            
            return redirect()->back()->with('success', 'Product removed from wishlist!');
            }
        }
    }
}