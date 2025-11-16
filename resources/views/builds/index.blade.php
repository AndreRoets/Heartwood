@extends('main')

@section('title', 'Builds')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-4xl font-bold text-white">Builds</h1>
        <a href="{{ route('builds.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit a Build</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($builds as $build)
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-white">{{ $build->name }}</h2>
                <p class="text-gray-400">by {{ $build->user->name }}</p>
                <p class="text-white mt-2">{{ Str::limit($build->description, 100) }}</p>
                <a href="{{ route('builds.show', $build) }}" class="text-blue-500 hover:underline mt-4 inline-block">Read more</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
