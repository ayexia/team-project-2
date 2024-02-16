<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ML Menswear') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div style="background: black">    
        <div class="p-6 text-teal-600 dark:text-teal-600">
                    <p class="text-lg font-bold">{{ __("You're logged in!") }}</p>
                    <p class="text-sm text-teal-800">{{ __("Check through our products...") }}</p>
                    <html>

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