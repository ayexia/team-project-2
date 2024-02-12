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
    <h1>Category details</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <div>
            <a href="{{route('categories.index')}}">Back</a>
        </div>
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
            </tr>
                 <tr>
                    <td>{{$category->name}}</td>
                 </tr>
        </table>
    </div>
</body>
</html>