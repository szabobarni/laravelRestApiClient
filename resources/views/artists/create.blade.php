<x-app-layout>
    <title>New Artist</title>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
        <h2>New Artist</h2>
    </x-slot>
        
    <div class="container">
        <form action="{{ route('artists.store') }}" method="POST">
            @csrf
            @method('POST')
            <label for="name">Name: </label>
            <input type="text" name="name"><br>

            <label for="nationality">Nationality: </label>
            <input type="text" name="nationality"><br>
            
            <label for="image">Image: </label>
            <input type="text" name="image"><br>
            
            <label for="description">Description: </label>
            <textarea name="description" rows="3" id=""></textarea>
            
            <label for="is_band">is_band: </label>
            <input type="text" name="is_band"><br>

            <button type="submit">OK</button>
            <a href="{{ route('artists.index') }}"><button type="button">Cancel</button></a>
        </form>
    </div>
</x-app-layout>
