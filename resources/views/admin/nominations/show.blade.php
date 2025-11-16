@extends('admin.layout')

@section('title', 'Nomination Details')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-center">Nomination Details</h1>
    <p class="text-gray-400 mb-8 text-center">Detailed information about the nomination.</p>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white mb-2">Nomination: {{ $nomination->name }}</h2>
        <p class="text-gray-300"><strong>Category:</strong> {{ $nomination->category->name }}</p>
        <p class="text-gray-300"><strong>Nominated By:</strong> {{ $nomination->user->name }} ({{ $nomination->user->email }})</p>
        <p class="text-gray-300"><strong>Status:</strong> {{ ucfirst($nomination->status) }}</p>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-white mb-2">Description:</h3>
        <p class="text-gray-300">{{ $nomination->description }}</p>
    </div>

    @if($nomination->images && count($nomination->images) > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white mb-2">Images:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  @foreach($nomination->images as $path)
  <img
    src="{{ Storage::url($path) }}"
    alt="Nomination Image"
    class="w-full h-48 object-cover rounded-lg shadow-md"
  />
@endforeach          </div>
        </div>
    @endif

    <div class="text-center mt-8">
        <a href="{{ route('admin.nominations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">Back to Nominations</a>
    </div>
</div>
@endsection
