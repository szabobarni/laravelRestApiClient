<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
</head>
<body>
    @foreach($artists as $artist)
    <a href="{{ route('artists.show', ['id' => $artist['id']]) }}"><p>{{ $artist['name'] }}</p></a>
    @endforeach
</body>
</html>