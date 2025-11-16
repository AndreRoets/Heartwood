@extends('main')

@section('title', $category->name . ' Nominations')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto relative">
    <h1 class="text-3xl font-bold mb-2 text-center">{{ $category->name }} Nominations</h1>
    <p class="text-gray-400 mb-8 text-center">Vote for your favorite nomination in the {{ $category->name }} category.</p>

    @if($processStatus && $processStatus->status == 'voting')
        <div class="text-center mb-4">
            <p class="text-xl font-semibold">Voting ends in:</p>
            <div id="voting-timer" class="text-2xl font-bold"></div>
        </div>

        @if($nominations->isEmpty())
            <div class="text-center text-gray-500 py-8">
                <p>No nominations are available for this category at this time.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($nominations as $nomination)
                    <div class="card-glass">
                        <h3 class="card-glass__title">{{ $nomination->title }}</h3>
                        <p class="card-glass__body">{{ $nomination->description }}</p>
                        @if($nomination->images)
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($nomination->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Nomination Image" class="rounded-lg object-cover w-full h-32">
                                @endforeach
                            </div>
                        @endif
                        <div class="flex items-center justify-end mt-4">
                            <form action="{{ route('nominations.vote', $nomination) }}" method="POST">
                                @csrf
                                <button type="submit" class="button button--primary">Vote</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm flex items-center justify-center z-10 rounded-lg">
            <div class="text-center text-white">
                @if($processStatus && $processStatus->status == 'nominating')
                    <p class="text-xl font-semibold mb-2">Voting will begin after nominations close.</p>
                    <p class="text-lg">Nominations end in:</p>
                    <div id="voting-starts-timer" class="text-2xl font-bold"></div>
                @elseif($processStatus && $processStatus->status == 'choosing')
                    <p class="text-xl font-semibold mb-2">Voting will begin after choosing phase.</p>
                    <p class="text-lg">Choosing ends in:</p>
                    <div id="choosing-ends-timer" class="text-2xl font-bold"></div>
                @elseif($processStatus && $processStatus->status == 'completed')
                    <p class="text-xl font-semibold mb-2">Voting has concluded.</p>
                @else
                    <p class="text-xl font-semibold mb-2">Voting is not currently active.</p>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 filter blur-sm">
            @foreach ($nominations as $nomination)
                <div class="card-glass">
                    <h3 class="card-glass__title">{{ $nomination->title }}</h3>
                    <p class="card-glass__body">{{ $nomination->description }}</p>
                    <div class="flex items-center justify-end mt-4">
                        <button type="button" class="button button--primary" disabled>Vote</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const processStatus = @json($processStatus);

        if (processStatus && processStatus.status === 'voting') {
            const votingTimerElement = document.getElementById('voting-timer');
            const votingEndsAt = new Date(processStatus.voting_ends_at).getTime();
            const updateVotingTimer = setInterval(() => {
                const now = Date.now();
                const distance = votingEndsAt - now;

                if (distance <= 0) {
                    clearInterval(updateVotingTimer);
                    votingTimerElement.textContent = "VOTING CLOSED";
                    // Consider a single reload to update the UI state if needed
                    // window.location.reload(); 
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                votingTimerElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }, 1000);
        }
    });
</script>
@endsection