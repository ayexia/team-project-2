<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
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
        $product = Product::findOrFail($item);
        $cart = session()->get('cart', []);
        if(isset($cart[$item])) {
            $cart[$item]['quantity']++;
        } else {
            $cart[$item] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image_url,
            ];
        }
        // $product->quantity--;
        // $product->save();
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product has been added to cart!');
    }
    
    public function updateCart(Request $request)
    {
        if($request->item && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->item]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Product added to cart.');
        }
    }
  
    public function deleteProduct(Request $request)
    {
        if($request->item) {
            $cart = session()->get('cart');
            if(isset($cart[$request->item])) {
                unset($cart[$request->item]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product successfully deleted.');
        }
    }
}
