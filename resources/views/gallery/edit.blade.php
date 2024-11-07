<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery</title>
</head>

<body>
    <form method="POST" action="{{route('gallery.update', $post->id)}}" enctype="multipart/form-data">
        <img src="{{ asset('storage/' . $post->picture) }}" style="width : 100px">
        @csrf
        <label for="picture">Photo</label>
        <input type="file" name="picture" id="picture">
        <br>
        <button type="submit">Update</button>
    </form>
</body>

</html>