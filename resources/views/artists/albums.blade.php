<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
        <h2>Albums</h2>
    </x-slot>

    <div class="container">
        @foreach($albums as $album)
            <div class="album-item">
                <a href="{{ route('artists.songs', ['artist_id' => $album['artist_id'], 'id' => $album['id']]) }}">
                    @if(!empty($album['cover']))
                        <img src="{{ $album['cover'] }}" alt="{{ $album['name'] }}">
                    @endif
                    <h2>{{ $album['name'] }}</h2>
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
