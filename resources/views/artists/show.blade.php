<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
        <h2>{{ $artist->name ?? 'Artist' }}</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-header">
                @if(!empty($artist->image))
                    <img src="{{ $artist->image }}" alt="{{ $artist->name }}">
                @endif
                <h2>{{ $artist->name }}</h2>
                @auth
                    <a href="{{ route('artists.edit', ['id' => $artist->id]) }}"><button>Edit</button></a>
                @endauth
                <a href="{{ route('artists.albums', ['id' => $artist->id]) }}"><button>Albums</button></a>
            </div>
            <div class="card-body">
                <h5>Description</h5>
                @if(!empty($artist->description))
                    <p>{!! nl2br(e($artist->description)) !!}</p>
                @else
                    <p class="text-muted">No description provided.</p>
                @endif

                <h5>Nationality</h5>
                @if(!empty($artist->nationality))
                    <p>{{ $artist->nationality }}</p>
                @else
                    <p class="text-muted">Nationality not specified.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
