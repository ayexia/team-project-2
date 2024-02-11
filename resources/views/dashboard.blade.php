<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Michelangelo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div style="background: linear-gradient(to right, rgb(68, 67, 122), rgb(218, 200, 164)); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-bold">{{ __("You're logged in!") }}</p>
                    <p class="text-sm text-gray-800">{{ __("Check through our products...") }}</p>
                    <html>
<style>
body {
  background: linear-gradient(rgb(0, 0, 128), rgb(210, 180, 140));
}
.right {
  position: absolute;
  top: 100px;
  right: 80px;
  width: 300px;
  border: none;
  padding: 5px;
}
.center {
  padding: 70px 0;
  text-align: center;
}
* {
  box-sizing: border-box;
}

form.example input[type=text] {
  padding: 10px;
  font-size: 12px;
  border: 1px solid grey;
  float: left;
  width: 75%;
  background: #f5f2d0;
  color: #918151;
  border-radius: 12px;
}
form.example button {
  float: left;
  width: 25%;
  padding: 10px;
  background: tan;
  color: white;
  font-size: 12px;
  border: 1px solid grey;
  border-left: none;
  cursor: pointer;
  border-radius: 12px;
}
form.example button:hover {
  background: #918151;
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
  border-radius: 12px;
}

.heading {
  font-size: 50px;
  font-family: Garamond;
  animation: color-change 5s infinite;
}

@keyframes color-change {
  0% { color: white; }
  50% { color: grey; }
  100% { color: white; }
}
.outline-glow {
  font-size: 48px;
  color: #FF7F50;
  -webkit-text-stroke: 2px rgb(210, 180, 140);
  text-shadow: 0 0 3px rgb(210, 180, 140);
</style>
</head>
<body>
<div class = "center heading outline-glow">
<?php

   echo "HELLO WORLD <br><br><font size ='5'> Work in progress";
 

?>

</div>
<div class = "right"> 
<?php
$user = auth()->user();
$cart = Cart::where('user_id', optional($user)->id)->first();
$count = 0;
if($cart){
$count = CartItem::where('cart_id', $cart->id)->sum('quantity');
}
?>
<ul>
<li><a href="/cart"><font size="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cart ({{$count}})  ||  <a href="{{route('orders')}}">Orders</a></font></a></li>
</li>
</ul>
<form action="/products" method="GET">
    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search for a product">
    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150""type="submit">Search</button>
</form>


</div>
</body>
</html>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>