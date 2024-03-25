<?php
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
$user = auth()->user();
$count = 0;
if ($user) {
    $cart = Cart::where('user_id', optional($user)->id)->first();
    if($cart){
    $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        }
    } else {
        $cartSession = session('cart', []);    
        foreach ($cartSession as $item) {
            $count += $item['quantity'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear - Search results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/websiteStyle.css') }}">
</head>
<style>
    .size-button {
        background-color: #fff; 
        color: #000; 
    }
    .size-button.selected {
        background-color: #20b2aa; 
        color: #fff; 
    }
</style>
<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/ML Menswear Logo.JPG') }}" alt="ML Menswear Logo">
            </a>
        </div>
        
        <div class="search-bar">
                <form action="/products" method="GET">
                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
        </div>
        
        <div class="user-icons">
        @if (Auth::check())
            <a href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-heart"></i></a>
        @endif
            <a href="{{ url('/cart') }}"><i class="fas fa-shopping-basket"> ({{$count}}) </i></a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/profile') }}"><i class="fas fa-user"></i></a>
                @else
                    <a href="{{ route('login') }}"><i class="fas fa-user"></i></a>
                @endauth
            @endif
        </div>
    </header>
    
    <nav>
    @if (!empty($categories))
    @foreach($categories as $cat)
    @if ($loop->iteration <= 5)
        <a href="{{ route('view.category', ['category' => $cat->name]) }}">{{ $cat->name }}</a>
    @endif
    @endforeach
    @endif
        <a href="{{route('orders')}}">Orders</a>
        <a href="">About Us</a>
        <a href="#">Contact Us</a>
    </nav>
    
    <div class="container">
        <div class="promo-banner">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
        </div>

        <h1>Products</h1>

        <div>
            @if(session()->has('success'))
               <div>
                  {{session('success')}}
               </div>
            @endif
        </div>
        <div class="productContainer">
            @if ($products->isNotEmpty())
                @foreach($products as $product)
                    <div class="product">
                    <a href="{{ route('product.show', ['product' => $product]) }}"><img src="{{ $product->image_url }}" alt="Product Image" style="width: 200px; height: 200px;" class="prodImg" /></a>            
                        <p>£{{$product->price}}</p>
                        <p>Colour: {{$product->colour}}</p>
                        <p>Brand: {{$product->brand}}</p>
                        <p>Sizes:</p>
                        @if ($product->sizes)
                            @foreach(json_decode($product->sizes) as $size)
                                {{$size}}
                                @unless ($loop->last)
                                    ,
                                @endunless
                            @endforeach
                        @endif
                    </div>
                @endforeach
            @else
                Records not found
            @endif
        </div>
    </div>
    
    <!-- Footer section -->
    <footer>
        <div class="business-details">
            <p>Email: info@mlmenswear.com</p>
            <p>Contact Number: +44 1234 567890</p>
            <p>Address: 123 Fashion Street, London, UK</p>
        </div>
        <p>&copy; 2024 ML Menswear. All rights reserved.</p>
    </footer>
</body>
</html>
