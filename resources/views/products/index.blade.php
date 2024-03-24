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
    <h1>List of Products</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <a href="{{route('home')}}">Back</a>
        </div>
        <br>
        <div>
            <a href="{{route('product.create')}}">Create a Product</a>
        </div><br>
        <form action="/product" method="GET">
  <div class="input-group" style="width: 250px;">
    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="float-right" placeholder="Search">
    <div class="input-group-append">
      <button type="submit" class="btn btn-default">
        Submit
      </button>
    </div>
  </div>
</form>
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
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @if ($products->isNotEmpty())
            @foreach($products as $product)
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
                                {{$size}}
                                @unless ($loop->last)
                                    ,
                                @endunless
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{$product->available}}</td>
                    <td>
                        <a href="{{route('product.show', ['product' => $product])}}">View</a>
                    </td>
                    <td>
                        <a href="{{route('product.edit', ['product' => $product])}}">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('product.destroy', ['product' => $product])}}">
                            @csrf
                            @method('delete')
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                 </tr>
            @endforeach
        </table>        
        @else
            Records not found
        @endif
    </div>
</body>
</html>