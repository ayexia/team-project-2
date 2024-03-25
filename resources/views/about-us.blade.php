<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ContactUs;

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
    <title>ML Menswear - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/websiteStyle.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/ML Menswear Logo.JPG') }}" alt="ML Menswear Logo">
            </a>
        </div>
        <?php $user = auth()->user(); ?>
        <div class="search-bar">
        <form action="/products" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        </div>
        <div class="user-icons">
        @if (Auth::check())
            <a href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-heart"></i></a>
        @endif
            <a href="{{ url('/cart') }}"><i class="fas fa-shopping-basket"> ({{ $count }}) </i></a>
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
        <a href="{{ route('orders') }}">Orders</a>
        <a href="{{ route('about-us') }}">About Us</a>
        <a href="{{ route('contact-us') }}">Contact Us</a>
    </nav>
    <div class="container">
        <div class="promo-banner">
            <div class="text-container">
                Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
            </div>
        </div>
        <div class="content">
            <h1>Welcome to ML Menswear</h1>
            <div class="container">
        <div class="content">
         
            <div class="heading">
                <h1>About Us</h1>
                <p>Welcome to ML Menswear, your go-to destination for premium men's athleisure wear. At ML Menswear, we are passionate about providing high-quality, stylish athleisure wear that seamlessly combines comfort, functionality, and fashion.</p>
            </div>
            <div class="about">
                <div class="about-content">
                    <h2>Mission Statement</h2>
                    <p>Our mission at ML Menswear is to empower men to live active, confident lifestyles by providing them with premium athleisure wear that inspires both performance and style. We are committed to innovation, sustainability, and customer satisfaction as we continue to grow and evolve in the ever-changing world of fashion.</p>
                </div>
                <div class="about-image">
                    <img src="images\Image 3.jpeg" alt="aboutus2">
                </div>
            </div>
            <div class="about">
                <div class="about-image">
                    <img src="images\Image 2.jpeg" alt="aboutus1">
                </div>
                <div class="about-content">
                    <h2>Our Values</h2>
                    <ul class="values">
                        <li>Quality: We believe in offering only the highest quality athleisure wear crafted from premium materials.</li>
                        <li>Style: Our designs are carefully curated to ensure you look and feel your best, whether you're hitting the gym or running errands.</li>
                        <li>Customer Satisfaction: Your satisfaction is our top priority. We strive to exceed your expectations with every purchase.</li>
                        <li>Inclusivity: We celebrate diversity and aim to offer sizes and styles that cater to all body types and preferences.</li>
                    </ul>
                </div>
            </div>
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
