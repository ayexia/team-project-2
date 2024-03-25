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
    @if ($category)
    <title>ML Menswear - {{ $category->name }}</title>
    @endif
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
        <?php
        $user = auth()->user();
        ?>
        <div class="search-bar">
        <form action="/products" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
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
        <a href="{{ route('about-us') }}">About Us</a>
        <a href="{{ route('contact-us') }}">Contact Us</a>

        <form action="{{ route('view.category', ['category' => $category->name]) }}" method="GET" id="sortForm">
    <select name="sort_by" onchange="this.form.submit()">
        <option value="">Filter by</option>
        <option value="price-low-high">Price: Low to High</option>
        <option value="price-high-low">Price: High to Low</option>
        <option value="name-a-z">Name: A-Z</option>
        <option value="name-z-a">Name: Z-A</option>
    </select>
        </form>

        
    </nav>
    <div class="container">
        <div class="promo-banner">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
    </div>
         
    <main>@if ($category)
    <h1>{{ $category->name }}</h1>
    @endif
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
           @php
            session()->forget('success');
            session()->save();
         @endphp
        @endif
    </div>
        <br>
       <div class= "productContainer">
       @if ($products->isNotEmpty())
    @foreach($products as $product)
        <div class="product">
            <a href="{{ route('product.show', ['product' => $product]) }}"><img src="{{ $product->image_url }}" alt="Product Image" style="width: 200px; height: 200px;" class="prodImg" /></a>            
            <h3>{{ $product->name }}</h3>
            <p>£{{ $product->price }}<br>
            Colour: {{ $product->colour }}<br>
            Brand: {{ $product->brand }}<br>
            Size:
            <div class="size-buttons">
                @if(isset($availableSizes[$product->id]))
                    @foreach($availableSizes[$product->id] as $size)
                        <button class="size-button" data-product-id="{{ $product->id }}">{{ $size }}</button>
                    @endforeach
                @endif
            </div>
            @if ($product && $product->available === 'yes')
    <form action="{{ route('add.to.cart', $product->id) }}" method="POST" class="btn btn-outline-danger">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        @if(isset($availableSizes[$product->id]) && count($availableSizes[$product->id]) > 0)
            <input type="hidden" class="selected-size" data-product-id="{{ $product->id }}" name="size" value="">
            <div class="input-group">
                <input type="number" name="quantity" min="1" max="{{ $product->quantity }}" value="1" class="form-control" style="width: 20px; height: 20px;" required>
                <div class="input-group-append">
                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}" type="submit" disabled>
                        <i class="fas fa-shopping-basket basket-icon"></i>
                        Add to Basket
                    </button>
                </div>
            </div>
        @else
            <input type="hidden" class="selected-size" data-product-id="{{ $product->id }}" name="size" value="">
            <div class="input-group">
                <input type="number" name="quantity" min="1" max="{{ $product->quantity }}" value="1" class="form-control" style="width: 20px; height: 20px;" required>
                <div class="input-group-append">
                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}" type="submit">
                        <i class="fas fa-shopping-basket basket-icon"></i>
                        Add to Basket
                        </button>
                        </div>
                        </div>
                    @endif
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
        </main>
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
  <script>
    document.querySelectorAll('.size-button').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const selectedSize = this.textContent.trim();
            const addToCartBtn = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);
            const selectedSizeInput = document.querySelector(`.selected-size[data-product-id="${productId}"]`);

            document.querySelectorAll(`.size-button[data-product-id="${productId}"]`).forEach(btn => {
                btn.classList.remove('selected');
            });

            this.classList.add('selected');
            selectedSizeInput.value = selectedSize;
            addToCartBtn.disabled = false;
        });
    });
</script>
</html>