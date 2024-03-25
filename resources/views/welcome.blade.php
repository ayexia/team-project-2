<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;

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
            <div class="text-container">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
        </div>
        </div>
        <div class="content">
            <h1>Welcome to ML Menswear</h1>
            <div class="image-container">
                <img id="homepageImage" src="images\Image 1.jpeg" alt="Homepage Image" class="homepage-image">
            </div>
             <p class="slogan">Elevate Your Style with Our Premium Collection</p>
             <button class="subscribe-button" onclick="subscribe()">Subscribe Now</button>
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
        const images = ["images/Image 1.jpeg", "images/Image 4.jpg", "images/Image 5.jpeg", "images/Image 6.jpeg"]; 
        let currentImageIndex = 0;
        let homepageImage = document.getElementById("homepageImage");

        function changeImage() {
            homepageImage.style.opacity = 0;
            setTimeout(() => {
                homepageImage.src = images[currentImageIndex];
                currentImageIndex = (currentImageIndex + 1) % images.length;
                homepageImage.style.opacity = 1;
            }, 500); 
        }

        setInterval(changeImage, 3000); // Change interval time as needed

        function subscribe() {
            let email = prompt("Enter your email to subscribe to our mailing list to stay updated and receive exclusive offers and discounts!");
            alert("Thank you for entering your email: " + email + "." + " You have been subscribed!" );
        }

        let subscribeButton = document.querySelector(".subscribe-button");
        subscribeButton.addEventListener("click", subscribe);
    </script>
</body>
</html>