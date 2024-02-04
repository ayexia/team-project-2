<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

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
        return view('products.show', ['product' => $product]);
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
}
