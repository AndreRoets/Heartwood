@extends('main')

@section('title', 'View Nomination')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">View Nomination</h1>
        <a href="{{ route('admin.nominations.index') }}" class="button button--secondary">Back to Nominations</a>
    </div>

    <div class="bg-gray-700 p-6 rounded-lg">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-white">Nominee: {{ $nomination->title }}</h2>
            <p class="text-gray-400">Category: {{ $nomination->category->name }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-white mb-2">Description</h3>
            <div class="prose prose-invert text-gray-300">
                {!! \Illuminate\Support\Str::markdown($nomination->description) !!}
            </div>
        </div>

        @if($nomination->images && count($nomination->images) > 0)
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-white mb-2">Images</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($nomination->images as $image)
                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $image) }}" alt="Nomination image for {{ $nomination->title }}" class="rounded-lg w-full h-auto object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <p class="text-gray-500 text-sm mt-6">Submitted by: {{ $nomination->user->name ?? 'Unknown' }} on {{ $nomination->created_at->format('M d, Y') }}</p>
    </div>
</div>
@endsection