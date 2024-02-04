<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Create a Category</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>



        @endif
    </div>
    <form method="post" action="{{route('categories.store')}}">
        @csrf
        @method('post')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" />
        </div><br>
        <div>
            <label>Slug</label>
            <input type="text" name="slug" placeholder="Slug" />
        </div><br>
        <div>
            <label>Status</label>
            <select name="status" id="status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Block</option></select>
        </div><br>
        <div>
            <input type="submit" value="Save a New Category" />
        </div>
    </form>
</body>
</html>