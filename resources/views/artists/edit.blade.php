<x-app-layout>
    <title>Edit Artist</title>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
        <h2>Edit Artist</h2>
    </x-slot>
        
    <div class="container">
        <form action="{{ route('artists.update', ['id' => $artist['id']]) }}" method="POST">
            @csrf
            @method('PATCH')
            <label for="name">Name: </label>
            <input type="text" name="name" value="{{ $artist['name'] }}"><br>
            <label for="nationality">Nationality: </label>
            <input type="text" name="nationality" value="{{ $artist['nationality'] }}"><br>
            <label for="image">Image: </label>
            <input type="text" name="image" value="{{ $artist['image'] }}"><br>
            <label for="description">Description: </label>
            <input type="text" name="description" value="{{ $artist['description'] }}"><br>
            <button type="submit">OK</button>
            <a href="{{ route('artists.show', ['id' => $artist['id']]) }}"><button type="button">Cancel</button></a>
        </form>
    </div>
</x-app-layout>
