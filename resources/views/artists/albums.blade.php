<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

<title>Albums</title>
@foreach($albums as $album)
    <div style="margin-bottom:12px;">
           <a href="{{ route('artists.songs', ['artist_id' => $album['artist_id'], 'id' => $album['id']]) }}"><h2>{{$album['name']}}</h2></a>
    </div>
@endforeach

</x-app-layout>