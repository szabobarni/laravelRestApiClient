<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

    <div class="artists-container">
        @auth
        <a href="{{ route('artists.create') }}" class="new-artist-btn">New Artist</a>
        @endauth

        @foreach($artists as $artist)
        <a href="{{ route('artists.show', ['id' => $artist['id']]) }}" class="artist-card-link">
            <div class="artist-item">
                <img src="{{ $artist['image'] }}" alt="{{ $artist['name'] }}">
                <div class="artist-info">
                    <h3>{{ $artist['name'] }}</h3>
                </div>

            @auth
            <form action="{{ route('artists.destroy', ['id' => $artist['id']]) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button class="delete-btn" onclick="return confirm('Are you sure you want to delete them?')">
                    Delete
                </button>
            </form>
            @endauth
            </div>
        </a>
        @endforeach
    </div>
</x-app-layout>