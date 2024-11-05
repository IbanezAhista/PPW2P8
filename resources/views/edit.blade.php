<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
</head>

<body>
    <form method="POST" action="{{route('users.update', $user->id)}}" enctype="multipart/form-data">
        <img src="{{ asset('storage/' . $user->photo) }}" style="width : 100px">
        @csrf
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo">
        <br>
        <button type="submit">Update</button>
    </form>
</body>

</html>