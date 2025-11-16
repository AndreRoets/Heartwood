@extends('admin.layout')

@section('title', 'Pending Nominations')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full mx-auto">
    <h1 class="text-3xl font-bold mb-6">Pending Nominations</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($nominationsByCategory->isEmpty())
        <div class="text-center py-12">
            <p class="text-xl text-gray-400">There are no pending nominations at this time.</p>
        </div>
    @else
        @foreach($nominationsByCategory as $categoryName => $nominations)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold border-b-2 border-gray-700 pb-2 mb-4">{{ $categoryName }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($nominations as $nomination)
                        <div class="bg-gray-700 rounded-lg shadow-md p-4 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ $nomination->title }}</h3>
                                <p class="text-gray-400 text-sm mb-2">Submitted by: {{ $nomination->user->name ?? 'Unknown' }}</p>
                                <p class="text-gray-300 mb-4">{{ \Illuminate\Support\Str::limit($nomination->description, 100) }}</p>
                            </div>
                            <div class="flex items-center justify-end space-x-2 mt-4">
                                <a href="{{ route('admin.nominations.show', $nomination) }}" class="button button--secondary button--small">View</a>
                                <form action="{{ route('admin.nominations.approve', $nomination) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="button button--primary button--small">Approve</button>
                                </form>
                                <form action="{{ route('admin.nominations.reject', $nomination) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="button button--danger button--small">Reject</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection