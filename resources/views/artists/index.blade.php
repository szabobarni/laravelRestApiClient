<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
</head>
<body>
    @auth
    <a href="{{ route('artists.create') }}"><button>New artist</button></a>
    @endauth
    @foreach($artists as $artist)
    <div style="margin-bottom:12px;">
        <a href="{{ route('artists.show', ['id' => $artist['id']]) }}"><strong>{{ $artist['name'] }}</strong></a>
        @auth
        <form action="{{ route('artists.destroy', ['id' => $artist['id']]) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete them?')">delete</button>
        </form>
        @endauth
    </div>
    @endforeach
</body>
</html>
</x-app-layout>