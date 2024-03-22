<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index($product)
    {
        $reviews = Review::where('product_id', $product)->get();
        return view('products.view', compact('reviews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'comment' => 'required',
            'rating' => 'required|numeric',
        ]);

        Review::create($data);

        return back()->with('success', 'Review added successfully!');
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->all());

        return back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully!');
    }
}
