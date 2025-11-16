@extends('layouts.app') {{-- Assuming you have a main layout file --}}

@section('title', 'Nominate')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Nominate for an Award</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($categories as $category)
            @php
                $userNomination = $nominations->where('category_id', $category->id)->first();
            @endphp
            <div class="bg-gray-800 p-6 rounded-lg">
                <h2 class="text-2xl font-bold mb-2">{{ $category->name }}</h2>
                <p class="text-gray-400 mb-4">{{ $category->description }}</p>

                @if($userNomination)
                    <div class="bg-gray-700 p-4 rounded-lg text-center">
                        <p class="font-semibold">You have already nominated "{{ $userNomination->name }}" for this category.</p>
                        <p class="text-sm text-gray-400">You cannot make another nomination in this category.</p>
                    </div>
                @else
                    <form action="{{ route('nominations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <div class="mb-4">
                            <label for="name-{{ $category->id }}" class="block mb-2">Nominee's Name</label>
                            <input type="text" name="name" id="name-{{ $category->id }}" class="w-full p-2 rounded bg-gray-700 border border-gray-600" required>
                        </div>
                        {{-- Add other fields like description, image etc. as needed --}}
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Submit Nomination</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection