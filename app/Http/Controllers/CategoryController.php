<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    $category = Category::orderBy('created_at', 'DESC');

    if ($request->has('keyword')) {
        $keyword = $request->input('keyword');
        $category->where('name', 'like', '%' . $keyword . '%');
    }

    $category = $category->get();

    return view('category.index', compact('category'));
}

    public function create(){
        return view('category.create');

    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required|numeric',
        ]);

        $newCategory = Category::create($data);

        return redirect(route('categories.index'));

    }
    public function edit(Category $category){
        return view('category.edit', ['category' => $category]);
    }

    public function show(Category $category){
        return view('category.show', ['category' => $category]);
    }

    public function update(Category $category, Request $request){
        
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'status' => 'required|numeric',
        ]);

        $category->update($data);

        return redirect(route('categories.index'))->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category){
        $category->delete();
        return redirect(route('categories.index'))->with('success', 'Category deleted successfully');
    }
}
