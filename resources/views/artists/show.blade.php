<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>
<title>{{ $artist->name }}</title>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ $artist->name ?? 'Artist' }}
            @auth
            <a href="{{ route('artists.edit', ['id' => $artist->id]) }}"><button>Edit</button></a>
            @endauth
            </h2>
            <a href="{{ route('artists.albums', ['id' => $artist->id]) }}"><button>Albums</button></a
        </div>

        <div class="card-body">
            <h5>Description</h5>
            @if(!empty($artist->description))
                <p>{!! nl2br(e($artist->description)) !!}</p>
            @else
                <p class="text-muted">No description provided.</p>
            @endif

            <h5 class="mt-3">Nationality</h5>
            @if(!empty($artist->nationality))
                <p>{{ $artist->nationality }}</p>
            @else
                <p class="text-muted">Nationality not specified.</p>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
