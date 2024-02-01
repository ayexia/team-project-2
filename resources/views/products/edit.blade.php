<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Edit a Product</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>



        @endif
    </div>
    <form method="post" action="{{route('product.update', ['product' => $product])}}">
        @csrf
        @method('put')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" value="{{$product->name}}"/>
        </div><br>
        <div>
            <label>Description</label>
            <input type="text" name="description" placeholder="Description" value="{{$product->description}}"/>
        </div><br>
        <div>
            <label>Price</label>
            <input type="text" name="price" placeholder="Price" value="{{$product->price}}"/>
        </div><br>
        <div>
            <label>Quantity</label>
            <input type="text" name="quantity" placeholder="Quantity" value="{{$product->quantity}}"/>
        </div><br>
        <div>
            <label>Image URL</label>
            <input type="text" name="image url" placeholder="Image URL" value="{{$product->image_url}}"/>
        </div><br>
        <div>
            <label>Colour</label>
            <input type="text" name="colour" placeholder="Colour" value="{{$product->colour}}"/>
        </div><br>
        <div>
            <label>Brand</label>
            <input type="text" name="brand" placeholder="Brand" value="{{$product->brand}}"/>
        </div><br>
        <div>
            <label>Size</label>
            <input type="text" name="size" placeholder="Size" value="{{$product->size}}"/>
        </div><br>
        <div>
            <label>Category</label>
            <input type="text" name="category" placeholder="Category" value="{{$product->category}}"/>
        </div><br>
        <div>
            <input type="submit" value="Update" />
        </div>
    </form>
</body>
</html>