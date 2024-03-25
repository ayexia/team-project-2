<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear - Order History</title>
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
        
    </nav>
    <div class="container">
        <div class="promo-banner">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
    </div>
         
    <main>
    <h1>Order History</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{ session('success') }}
           </div>
           @php
            session()->forget('success');
            session()->save();
           @endphp
        @endif
    </div>
    <br>
    <div class="productContainer">
        <?php
            $user = auth()->user();
            if ($user) {
                $orders = Order::where('user_id', $user->id)->get();
            } else {
                $guestIdentifier = session()->get('guest_identifier');
                $orders = Order::where('guest_identifier', $guestIdentifier)->get();
            }
        ?>
        @if($orders->isNotEmpty())
            @foreach($orders as $order) 
                <?php $orderItems = OrderItem::where('order_id', $order->id)->get(); ?>
                <div class="order">
                    <h2>Order Number: {{ $order->id }}</h2>
                    @foreach ($orderItems as $item)
                        <div class="order-item">
                            <img src="{{ $item->product->image_url }}" class="prodImg"/>
                            <div class="order-item-details">
                                <h3>{{ $item->product->name }}</h3>
                                <p>Size: {{ $item->size }}</p>
                                <p>£{{ $item->product->price }}</p>
                                <p>Quantity: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="order-details">
                        <p><span class="teal">Address:</span> {{ $order->address }}</p>
                        <p><span class="teal">Status:</span> {{ $order->status }}</p>
                        <p><span class="teal">Total Price:</span> £{{ $order->total_price }}</p>
                        <p><span class="teal">Order Date:</span> {{ $order->order_date }}</p>
                        @if ($order->status === 'Pending' || $order->status === 'Processing')
                            <form method="POST" action="{{ route('order.cancel', ['order' => $order->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Cancel Order</button>
                            </form>
                        @elseif ($order->status === 'Delivered')
                            <form method="POST" action="{{ route('order.return', ['order' => $order->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Return Order</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p>No orders placed</p>
        @endif
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
    </main>

<style>
/* Order History page styles */
.order {
    width: calc(33.33% - 40px); 
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: inline-block;
    vertical-align: top;
    margin-right: 20px; 
    margin-bottom: 20px;
}

.order h2 {
    color: #008080;
    font-size: 24px;
    margin-bottom: 10px;
}

.order-item {
    display: flex;
    margin-bottom: 15px;
}

.order-item img {
    width: 120px;
    height: 120px;
    border-radius: 5px;
    margin-right: 20px;
}

.order-item-details h3 {
    color: #008080;
    font-size: 18px;
    margin: 0;
}

.order-details {
    margin-top: 20px;
}

.order-details p {
    margin: 5px 0;
}

.order-details .teal {
    color: #008080;
}



.btn {
    color: #fff;
    background-color: #008080;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #006666;
}

</style>