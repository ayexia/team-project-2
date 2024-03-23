<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Review;

$user = auth()->user();
if ($user) {
    $cart = Cart::where('user_id', optional($user)->id)->first();
    $count = 0;
    if($cart){
    $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        }
    } else {
        $cartSession = session('cart', []);
        $count = 0;
    
        foreach ($cartSession as $item) {
            $count += $item['quantity'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="authors" content="Ayesha, Nagina">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$product->name}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/websiteStyle.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="/images/ML Menswear Logo.JPG" alt="ML Menswear Logo">
            </a>
        </div>
        <?php
        $user = auth()->user();
        ?>
        <div class="search-bar">
        @if ($user && $user->usertype === 'admin') 
        <form action="/product" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        @else
        <form action="/products" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        @endif
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
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
        
    </nav>
    <div class="container">
        <div class="promo-banner">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
    </div>
    <main>
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
    <div class="product-container">
      <div class="product-image">
      <img src="{{$product->image_url}}" alt="Product Image" style="width: 400px; height: 400px;">                  
      </div>
      <div class="product-description">
        <h2 class="product-title">{{$product->name}}</h2>
        <p class="product-price">£{{$product->price}}</p>
        <div class="product-details">
        <!-- renders and displays new lines from table data -->
            <p>{!! nl2br(e($product->description)) !!}</p><br>
            <p>Colour: {{$product->colour}}<br>
               Brand: {{$product->brand}}<br>
               Size: {{$product->size}}<br>
               In: {{ $product->category->name }}</p><br>
        </div>
        @if ($product && $product->available === 'yes')
        <form action="{{ route('add.to.cart', $product->id) }}" method="POST" class="btn btn-outline-danger">
    @csrf
    <input type="hidden" name="product_id" value="{{$product->id}}">
    <div class="input-group">
        <input type="number" name="quantity" min="1" max="{{$product->quantity}}" value="1" class="form-control" style="width: 30px; height: 25px;" required>
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-shopping-basket basket-icon"></i>
                Add to Basket
            </button>
        </div>
    </div>
</form>
                @endif
</div>
<br>
<form action="{{ route('add.to.wishlist', $product->id) }}" class="btn btn-outline-danger"> 
        @csrf
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-shopping-basket basket-icon"></i>
                Add to Wishlist
            </button>
        </div>
</form>
      </div>
</div>

<div class="product-container">
    <h3>Reviews</h3><br>
    @foreach ($reviews as $review)
        <div class="review">
            <p>User: {{ $review->user->name }}</p>
            <p>Comment: {{ $review->comment }}</p>
            <p>Rating: {{ $review->rating }}</p>
            @if(auth()->check() && auth()->id() === $review->user_id)
                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label for="comment">Update Comment:</label><br>
                        <textarea name="comment" id="comment" cols="30" rows="2">{{ $review->comment }}</textarea><br>
                    </div>
                    <div>
                        <label for="rating">Update Rating (1-5):</label><br>
                        <input type="number" name="rating" id="rating" min="1" max="5" value="{{ $review->rating }}">
                    </div>
                    <button type="submit">Update Review</button>
                </form>

                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete Review</button>
                </form>
            @endif
        </div>
    @endforeach

    <br><br>
    @if(auth()->check())
        <h2>Add a Review</h2><br>
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div>
                <label for="comment">Comment:</label><br>
                <textarea name="comment" id="comment" cols="30" rows="5"></textarea><br>
            </div>
            <div>
                <label for="rating">Rating (1-5):</label><br>
                <input type="number" name="rating" id="rating" min="1" max="5">
            </div>
            <button type="submit">Submit Review</button>
        </form>
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
</html>