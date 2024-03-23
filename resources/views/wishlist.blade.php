<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
$user = auth()->user();
if ($user) {
    $cart = Cart::where('user_id', optional($user)->id)->first();
    $count = 0;
    if($cart){
    $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        }
    } else {
        $cartSession = session('cart', []);
        $wishlistSession = session('wishlist', []);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear - Wishlist</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/websiteStyle.css">
    
</head>

<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="images\ML Menswear Logo.JPG" alt="ML Menswear Logo">
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
    <h1>Wishlist</h1>
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
        <?php
 $user = auth()->user();
            $wishlist = Wishlist::where('user_id', auth()->id())->get();
            $wishlistItems = [];
            if ($user) {
                $wishlistItems = Wishlist::where('user_id', $user->id)->with('product')->get();
            }
            ?>
            @if ($user && $wishlistItems)
            @foreach($wishlistItems as $wishlistItem) 
      <div class="product">
      <img src="{{ $wishlistItem->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
      <h3>{{$wishlistItem->product->name}}</h3>
        <p>£{{$wishlistItem->product->price}}</p>
        <p><a class="btn btn-outline-danger" href="{{route('remove.from.wishlist', $wishlistItem->id)}}">Delete</a></p>
        @if ($wishlistItem->product->available === 'yes')
    <form action="{{ route('add.to.cart', $wishlistItem->product->id) }}" method="POST" class="btn btn-outline-danger">
        @csrf
        <input type="hidden" name="product_id" value="{{ $wishlistItem->product->id }}">
        <div class="input-group">
            <input type="number" name="quantity" min="1" max="{{ $wishlistItem->product->quantity }}" value="1" class="form-control" style="width: 20px; height: 20px;" required>
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
@endforeach
    
        <?php  
        $wishlistSession = session('wishlist', []);
        ?>
        @dump(session('wishlist'))
 @elseif (!$user && $wishlistSession)
            @foreach($wishlistSession as $item)
                <div class="product">
                    <img src="{{ $item['image_url'] }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
                    <h3>{{ $item['name'] }}</h3>
                    <p>£{{ $item['price'] }}</p>
                    <p><a class="btn btn-outline-danger" href="{{route('remove.from.wishlist', ['item' => $item['id']])}}">Delete</a></p>
                    @if ($wishlistItem->product->available === 'yes')
    <form action="{{ route('add.to.cart', $wishlistItem->product->id) }}" method="POST" class="btn btn-outline-danger">
        @csrf
        <input type="hidden" name="product_id" value="{{ $wishlistItem->product->id }}">
        <div class="input-group">
            <input type="number" name="quantity" min="1" max="{{ $wishlistItem->product->quantity }}" value="1" class="form-control" style="width: 20px; height: 20px;" required>
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
@endforeach
                    
        @else
        Wishlist is empty!
        @endif
    </div>
    <?php dd(session('guest_identifier')); ?><?php dd(session('guest_identifier')); ?>
    </main>


    <style>
    /* Wishlist page styles */

main {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    position: relative;
}

.productContainer {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    justify-items: center;
}

.product {
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
}

.product h3 {
    margin-top: 10px;
    font-size: 18px;
}

.product p {
    font-size: 16px;
    margin-top: 5px;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
    padding: 5px 10px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff;
}

.input-group {
    margin-top: 10px;
}

.input-group input[type="number"] {
    width: 50px;
    height: 30px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.input-group-append {
    display: flex;
    align-items: center;
}

.basket-icon {
    margin-right: 5px;
}
</style>
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