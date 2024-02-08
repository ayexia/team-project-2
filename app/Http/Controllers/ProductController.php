<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC');
    
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $products->where('name', 'like', '%' . $keyword . '%');
        }
    
        $products = $products->get();

        $usertype=Auth()->user()->usertype;

            if($usertype=='user')
            {
                return view('products.search', compact('products'));
            }
            else if($usertype=='admin')
            {
                return view('products.index', compact('products'));
            }
    }

    public function create(){
        $data = [];
        $category = Category::orderBy('name', 'ASC')->get();
        $data['category'] = $category;
        return view('products.create', $data);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|decimal:0,2',
            'quantity' => 'required|numeric',
            'image_url' => 'required|url',
            'colour' => 'nullable',
            'brand' => 'nullable',
            'size' => 'required',
            'category_id' => 'required|numeric',
            'available' => 'required|in:yes,no',
        ]);

        $newProduct = Product::create($data);

        return redirect(route('product.index'));

    }

    public function edit(Product $product){
        $data = [];
        $category = Category::orderBy('name', 'ASC')->get();
        $data['category'] = $category;
        return view('products.edit', ['product' => $product], $data);
    }

    public function show(Product $product){
        $usertype=Auth()->user()->usertype;

            if($usertype=='user')
            {
                return view('products.view', ['product' => $product]);
            }
            else if($usertype=='admin')
            {
                return view('products.show', ['product' => $product]);
            }
    }

    public function update(Product $product, Request $request){
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|decimal:0,2',
            'quantity' => 'required|numeric',
            'image_url' => 'required|url',
            'colour' => 'nullable',
            'brand' => 'nullable',
            'size' => 'required',
            'category_id' => 'required|numeric',
            'available' => 'required|in:yes,no',
        ]);

        $product->update($data);

        return redirect(route('product.index'))->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect(route('product.index'))->with('success', 'Product deleted successfully');
    }

    public function cart()
    {
        return view('cart');
    }

    public function addToCart($item)
    {
    $user = auth()->user();
    $product=Product::find($item);
    $cart = Cart::where('user_id', auth()->user()->id)->first();
    $cartItem = CartItem::where('product_id', $product->id)->first();
    
    if (!$cart) {
        $cart = new Cart();
        $cart->user_id = auth()->user()->id;
        $cart->save();
    }
    if ($cartItem) {
        $cartItem->quantity += 1;
        $cartItem->save();

    } else {
        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        $cartItem->save();
    }

    return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function removeFromCart($item){

        $product=CartItem::find($item);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted from cart!');
    }
}