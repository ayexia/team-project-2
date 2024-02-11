
<?php
use App\Models\User;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        
        header {
            background-color: #000;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
    
        
        }


    
        
        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }
        
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin-right: 20px;
        }
        
        .container {
            max-width: 100%;
            padding: 20px;
            text-align: center;
            margin: 0 auto;
        }
        
        .content {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }
          .promo-banner {
                background-color: #008080;
                color: #fff;
                padding: 10px 0;
                text-align: center;
                position: relative;
                bottom: 20px;
                overflow: hidden; /* Hide overflow text */
          }

        
        .text-container {
    color: #fff;
    animation: slideText 12s linear infinite;
    white-space: nowrap; /* Prevent text from wrapping */
    display: inline-block; /* Make container only as wide as the text */
}

@keyframes slideText {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}
        
        footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
            bottom: 0;
            width: 100%;
        }
        
        .teal {
            color: #008080;
        }
        
        /* Homepage styles */
        .homepage-image {
            margin-top: 50px;
            margin-bottom: 50px;
            max-width: 100%; /* Set maximum width to 100% */
            height: auto; /* Ensure the aspect ratio is maintained */
        }
        
        .slogan {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            position: relative;
    bottom: 80px;
        }
        
        .search-bar {
            text-align: center;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex: 1;
        }
        
        .search-bar input[type="text"] {
            margin-right: 10px;
        }
        
        .user-icons {
            display: flex;
            align-items: center;
        }
        
        .user-icons i {
            margin: 10px;
            color: #fff;
            cursor: pointer;
        }
        
        .image-container {
            position: relative;
            display: inline-block;
            bottom: 50px;
        }
        .homepage-image {
  position: relative;
  top: 0;
  left: 0;
  object-fit: cover;
        }
        
        .shop-now-btn {
            position: absolute;
            top: 160px; /* Adjust as needed */
            left: 30px; /* Adjust as needed */
            background-color: #008080;
            color: #fff;
            padding: 20px 50px; /* Adjust as needed */
            border: none;
            border-radius: 10px; /* Adjust as needed */
            text-decoration: none;
            font-size: 20px; /* Adjust as needed */
            transition: background-color 0.3s ease;
        }
        
        .shop-now-btn:hover {
            background-color: #006666;
        }

      
        
        
        @media (max-width: 768px) {
            nav {
                display: none;
            }
        }

   
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="your-logo.png" alt="ML Menswear Logo">
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
            <i class="fas fa-heart"></i>
            <a href="{{ url('/cart') }}" i class="fas fa-shopping-basket"></i></a>
            @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" i class="fas fa-user"></i></a>
                    @else
                        <a href="{{ route('login') }}" i class="fas fa-user"></i></a>
                    @endauth
            @endif
    </div>
    </header>
    <nav>
    <a href="{{ route('tops') }}">Tops</a>
        <a href="{{ route('coats-and-jackets') }}">Coats & Jackets</a>
        <a href="{{ route('trousers') }}">Trousers</a>
        <a href="{{ route('shoes') }}">Shoes</a>
        <a href="{{ route('accessories') }}">Accessories</a>
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
                <img id="homepageImage" src="C:\Users\nadia\OneDrive\Documents\website 3 img 2.avif" alt="Homepage Image" class="homepage-image">
            </div>
            @if ($user && $user->usertype === 'admin') 
             <a href="{{route('product.index')}}" class="shop-now-btn">Shop Now</a>
             @else
             <a href="{{route('products')}}" class="shop-now-btn">Shop Now</a>
            @endif
            <p class="slogan">Elevate Your Style with Our Premium Collection</p>
    </div>
    <footer>
        <p>&copy; 2024 ML Menswear. All rights reserved.</p>
    </footer>
</body>
</html>