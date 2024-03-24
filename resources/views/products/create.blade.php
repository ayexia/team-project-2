<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="authors" content="Ayesha, Nagina">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Create a Product</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <form method="post" action="{{route('product.store')}}">
        @csrf
        @method('post')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" />
        </div><br>
        <div>
            <label>Description</label><br>
            <textarea name="description" cols="30" rows="10" placeholder="Description"></textarea>
        </div><br>
        <div>
            <label>Price</label>
            <input type="text" name="price" placeholder="Price" />
        </div><br>
        <div>
            <label>Quantity</label>
            <input type="text" name="quantity" placeholder="Quantity" />
        </div><br>
        <div>
            <label>Image URL</label>
            <input type="text" name="image url" placeholder="Image URL" />
        </div><br>
        <div>
            <label>Colour</label>
            <input type="text" name="colour" placeholder="Colour" />
        </div><br>
        <div>
            <label>Brand</label>
            <input type="text" name="brand" placeholder="Brand" />
        </div><br>
        <div id="sizeInputs">
            <label>Sizes</label>
            <div>
                <input type="text" name="sizes[]" placeholder="Size" />
                <button type="button" onclick="addSizeField()">Add Size</button>
            </div>
        </div><br>
        <div>
          <select name="category_id" id="category_id" class="form-control">
            <option value ="">Category</option>
          @if ($category->isNotEmpty())
            @foreach ($category as $category)
            <option value="{{$category->id}}">{{ $category->name }}</option>
            @endforeach
          @endif 
          </select>
        </div><br>
        <div class="form-group">
            <label for="available">Available?</label>
            <select name="available" id="available" class="form-control">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
            </select>
        </div><br>
        <div>
            <input type="submit" value="Save a New Product" />
        </div>
    </form>
    <script>
        function addSizeField() {
            const sizeInputs = document.getElementById('sizeInputs');
            const div = document.createElement('div');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'sizes[]';
            input.placeholder = 'Size';
            div.appendChild(input);
            sizeInputs.appendChild(div);
        }
    </script>
</body>
</html>