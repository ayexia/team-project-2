<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $categories = Category::all();

        return view('welcome', compact('categories'));
           
    }
}