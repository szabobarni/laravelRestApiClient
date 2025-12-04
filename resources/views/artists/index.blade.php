<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
</head>
<body>
    <a href="{{ route('artists.create') }}"><button>New artist</button></a>
    @foreach($artists as $artist)
    <div style="margin-bottom:12px;">
        <a href="{{ route('artists.show', ['id' => $artist['id']]) }}"><strong>{{ $artist['name'] }}</strong></a>
        <form action="{{ route('artists.destroy', ['id' => $artist['id']]) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete them?')">delete</button>
        </form>
    </div>
    @endforeach
</body>
</html>