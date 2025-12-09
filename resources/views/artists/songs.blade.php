<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

<title>Songs</title>
@foreach($songs as $song)
    <div style="margin-bottom:12px;">
           <h2>{{$song['name']}}</h2>
    </div>
@endforeach

</x-app-layout>