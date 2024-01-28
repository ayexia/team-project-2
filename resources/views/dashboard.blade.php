<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-800 to-gray-500 dark:from-blue-900 dark:to-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-bold">{{ __("You're logged in!") }}</p>
                    <p class="text-sm text-gray-600">{{ __("Check through our products...") }}</p>
                    <html>
<head>
    <title>Michelangelo</title>
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
.nav {
  background-color: #000;
  opacity: 50%;
  overflow: hidden;
  margin: 97px 0px 0px 0px;
  border-radius: 12px;
  border: 1px solid tan;
}

.nav a {
  float: left;
  display: block;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  color: tan;
  border-bottom: 3px solid transparent;
  margin: 0px 60px 0px 60px;
}

.nav a:hover {
  border-bottom: 3px solid tan;
}

.nav a.active {
  border-bottom: 3px solid tan;
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
<div class="nav">
  <a href="#home">Home</a>
  <a href="#product">Products</a>
  <a href="#contact">Contact</a>
  <a href="#about">About Us</a>
</div>
<div class = "center heading outline-glow">
<?php

   echo "<br><br><br>HELLO WORLD <br><br><font size ='5'> Work in progress";
 

?>
</div>
<div class = "right"> 
    <form class = "example" method="GET" action="search.php">
        <input type="text" name="query" placeholder="Search for an item or brand">
        <button type="submit">Search</button>
    </form>
	</div>
</body>
</html>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>