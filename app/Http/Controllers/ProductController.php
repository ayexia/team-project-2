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

/**
 * Authors: Ayesha and Nagina
 */

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

        return view('products.index', compact('products'));
    
    }

    public function indexForUser(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC');
    
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $products->where('name', 'like', '%' . $keyword . '%');
        }
    
        $products = $products->get();

        return view('products.search', compact('products'));
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
        $reviews = $product->reviews;
        return view('products.view', ['product' => $product, 'reviews' => $reviews]);
        
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

    public function viewTops(Request $request){
        $category = Category::where('name', 'Tops')->first();
        $products = Product::where('category_id', $category->id);
    
        if ($request->has('sort_by')) {
            $sort_by = $request->input('sort_by');
            switch ($sort_by) {
                case 'price-low-high':
                    $products->orderBy('price', 'ASC');
                    break;
                case 'price-high-low':
                    $products->orderBy('price', 'DESC');
                    break;
                case 'name-a-z':
                    $products->orderBy('name', 'ASC');
                    break;
                case 'name-z-a':
                    $products->orderBy('name', 'DESC');
                    break;
                }
            }
        
        $products = $products->get();
        return view('products.tops', compact('products'));
    }

    public function viewTrousers(){
        return view('products.trousers');
    }

    public function viewShoes(){
        return view('products.shoes');
    }

    public function viewCoatsAndJackets(){
        return view('products.coats-and-jackets');
    }

    public function viewAccessories(){
        return view('products.accessories');
    }
}