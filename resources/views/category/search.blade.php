<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>List of Categories</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <form action="/categories" method="GET">
  <div class="input-group" style="width: 250px;">
    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="float-right" placeholder="Search">
    <div class="input-group-append">
      <button type="submit" class="btn btn-default">
        Submit
      </button>
    </div>
  </div>
</form>
        <div>
        <a href="{{route('home')}}">Back</a>
        </div>
        <br>
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>View</th>
            </tr>
            @if ($category->isNotEmpty())
            @foreach($category as $category)
                 <tr>
                    <td>{{$category->name}}</td> <td>
                        <a href="{{route('categories.show', ['category' => $category])}}">View</a>
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