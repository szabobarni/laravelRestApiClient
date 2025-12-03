@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('artists.index') }}" class="btn btn-secondary mb-3">Back to artists</a>

    <div class="card">
        <div class="card-header">
            <h2>{{ $artist->name ?? 'Artist' }}</h2>
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
@endsection