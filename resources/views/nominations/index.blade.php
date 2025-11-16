@extends('main')

@section('title', 'Vote')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto relative">
    <h1 class="text-3xl font-bold mb-2 text-center">Vote</h1>
    <p class="text-gray-400 mb-8 text-center">Select a category to view nominations and cast your vote.</p>

    @if($processStatus && $processStatus->status == 'voting')
        <div class="text-center mb-4">
            <p class="text-xl font-semibold">Voting ends in:</p>
            <div id="voting-timer" class="text-2xl font-bold"></div>
        </div>

        @if($categories->isEmpty())
            <div class="text-center text-gray-500 py-8">
                <p>No categories available for voting at this time. Please check back later.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($categories as $category)
                    <a href="{{ route('nominations.category.show', $category) }}" class="card-glass block relative {{ in_array($category->id, $votedCategories) ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <h3 class="card-glass__title">{{ $category->name }}</h3>
                        <p class="card-glass__body">{{ $category->description }}</p>
                        @if(in_array($category->id, $votedCategories))
                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Voted</span>
                        @endif
                    </a>
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
            @foreach ($categories as $category)
                <div class="card-glass">
                    <h3 class="card-glass__title">{{ $category->name }}</h3>
                    <p class="card-glass__body">{{ $category->description }}</p>
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
                    // Optionally, you could refresh the page once to show the final state.
                    // window.location.reload();
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                votingTimerElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }, 1000);
        } else if (processStatus && processStatus.status === 'nominating') {
            const votingStartsTimerElement = document.getElementById('voting-starts-timer');
            const nominationEndsAt = new Date(processStatus.nomination_ends_at).getTime();
            const updateNominationEndsTimer = setInterval(() => {
                const now = Date.now();
                const distance = nominationEndsAt - now;
                if (distance <= 0) {
                    clearInterval(updateNominationEndsTimer);
                    votingStartsTimerElement.textContent = "NOMINATIONS CLOSED";
                    return;
                }
                // ... update timer display ...
            }, 1000);
        }
    });
</script>
@endsection
