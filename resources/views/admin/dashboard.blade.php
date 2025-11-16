@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
    <p>Welcome to the admin panel, {{ auth()->user()->name }}!</p>

    <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">Manage Nomination/Voting Process</h2>

        <p class="mb-4">Current Process Status: <span class="font-bold">{{ ucfirst($processStatus->status) }}</span></p>

        <div class="flex space-x-4">
            {{-- Start Nomination --}}
            <form action="{{ route('admin.process.startNomination') }}" method="POST">
                @csrf
                <button type="submit" class="font-bold py-2 px-4 rounded text-white
                    @if(!in_array($processStatus->status, ['inactive', 'completed'])) bg-gray-500 cursor-not-allowed @else bg-blue-500 hover:bg-blue-700 @endif"
                    @if(!in_array($processStatus->status, ['inactive', 'completed'])) disabled @endif>
                    Start Nominations
                </button>
            </form>

            {{-- Start Voting --}}
            <form action="{{ route('admin.process.startVoting') }}" method="POST">
                @csrf
                <button type="submit" class="font-bold py-2 px-4 rounded text-white
                    @if($processStatus->status !== 'nominating') bg-gray-500 cursor-not-allowed @else bg-green-500 hover:bg-green-700 @endif"
                    @if($processStatus->status !== 'nominating') disabled @endif>
                    Start Voting
                </button>
            </form>

            {{-- Show Results --}}
            <form action="{{ route('admin.process.showResults') }}" method="POST">
                @csrf
                <button type="submit" class="font-bold py-2 px-4 rounded text-white
                    @if(!in_array($processStatus->status, ['voting', 'completed'])) bg-gray-500 cursor-not-allowed @else bg-purple-500 hover:bg-purple-700 @endif"
                    @if(!in_array($processStatus->status, ['voting', 'completed'])) disabled @endif>
                    Show Results
                </button>
            </form>

            {{-- Reset Process --}}
            <form action="{{ route('admin.process.stop') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset the process? This will delete ALL nominations, votes, and associated images. This action cannot be undone.');">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Reset
                </button>
            </form>
        </div>
    </div>
@endsection