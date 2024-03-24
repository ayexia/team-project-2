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
            @if (auth()->check() && auth()->user()->usertype === 'admin') 
                <form action="/product" method="GET">
                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            @else
                <form action="/products" method="GET">
                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            @endif
        </div>
        
        <div class="user-icons">
            <a href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
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
            @foreach($categories as $category)
                @if ($loop->iteration <= 5)
                    <a href="{{ route('view.category', ['category' => $category->name]) }}">{{ $category->name }}</a>
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
                        <img src="{{$product->image_url}}" alt="Product Image" style="width: 200px; height: 200px;" class="prodImg" />                  
                        <h3>{{$product->name}}</h3>
                        <p>£{{$product->price}}</p>
                        <p>Colour: {{$product->colour}}</p>
                        <p>Brand: {{$product->brand}}</p>
                        <p>Sizes:</p>
                        <div class="size-buttons">
                            @if ($product->sizes)
                                @foreach(json_decode($product->sizes) as $size)
                                    <button class="size-button">{{$size}}</button>
                                @endforeach
                            @endif
                        </div>
                        <p>
                            <a href="{{route('product.show', ['product' => $product])}}">View</a>
                        </p>
                        @if ($product && $product->available === 'yes')
    <form action="{{ route('add.to.cart', $product->id) }}" method="POST" class="btn btn-outline-danger">
        @csrf
        <input type="hidden" name="product_id" value="{{$product->id}}">
        <input type="hidden" id="selectedSize" name="size" value="">
        <div class="input-group">
            <input type="number" name="quantity" min="1" max="{{$product->quantity}}" value="1" class="form-control" style="width: 30px; height: 25px;" required>
            <div class="input-group-append">
                <button id="addToCartBtn" class="btn btn-primary" type="submit" disabled>
                    <i class="fas fa-shopping-basket basket-icon"></i>
                    Add to Basket
                        </button>
                    </div>
                </div>
            </form>
        @else
            <p class="sold-out-text">Sold Out</p>
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

    <script>
    document.querySelectorAll('.product').forEach(product => {
        const addToCartBtn = product.querySelector('.add-to-cart-btn');
        const sizeButtons = product.querySelectorAll('.size-button');

        const handleSizeButtonClick = function() {
            const selectedSize = this.textContent.trim();
            sizeButtons.forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            addToCartBtn.disabled = false;
            const selectedSizeInput = product.querySelector('.selected-size');
            selectedSizeInput.value = selectedSize;
        };

        if (sizeButtons.length > 0) {
            sizeButtons.forEach(button => {
                button.addEventListener('click', handleSizeButtonClick);
            });
            addToCartBtn.disabled = true;
        } else {
            addToCartBtn.disabled = false;
        }
    });
</script>
</body>
</html>