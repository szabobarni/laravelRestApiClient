<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
        <h2>Songs</h2>
    </x-slot>

    <div class="container">
        @foreach($songs as $song)
            <div class="song-item">
                <h2>{{ $song['name'] }}</h2>
            </div>
        @endforeach
    </div>
</x-app-layout>
