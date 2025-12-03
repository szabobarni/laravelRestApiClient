<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
</head>
<body>
    @foreach($artists as $artist)
    <div style="margin-bottom:12px;">
        <a href="{{ route('artists.show', ['id' => $artist['id']]) }}"><strong>{{ $artist['name'] }}</strong></a>
    </div>
    @endforeach
</body>
</html>