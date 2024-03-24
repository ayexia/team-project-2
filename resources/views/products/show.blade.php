<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="authors" content="Ayesha, Nagina">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Product details</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <div>
            <a href="{{route('product.index')}}">Back</a>
        </div>
        <br>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image URL</th>
                <th>Colour</th>
                <th>Brand</th>
                <th>Sizes</th>
                <th>Category</th>
                <th>Available?</th>
            </tr>
                 <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->image_url}}</td>
                    <td>{{$product->colour}}</td>
                    <td>{{$product->brand}}</td>
                    <td>
                    @if ($product->sizes)
                @foreach(json_decode($product->sizes) as $size)
                    {{$size}},
                @endforeach
                @endif
                    </td>   
                    <td>{{ $product->category->name }}</td>
                    <td>{{$product->available}}</td>
                 </tr>
        </table>
    </div>
</body>
</html>