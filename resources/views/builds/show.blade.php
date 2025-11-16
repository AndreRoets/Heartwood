@extends('main')

@section('title', $build->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-4xl font-bold text-white mb-2">{{ $build->name }}</h1>
        <p class="text-gray-400 mb-4">by {{ $build->user->name }}</p>
        
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-white mb-2">Description</h2>
            <p class="text-white">{{ $build->description }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-white mb-2">Coordinates</h2>
            <p class="text-white">{{ $build->coordinates }}</p>
        </div>

        @can('delete', $build)
            <div class="mt-4">
                <form action="{{ route('builds.destroy', $build) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Build</button>
                </form>
            </div>
        @endcan

        <div>
            <h2 class="text-2xl font-bold text-white mb-2">Required Items</h2>
            <ul>
                @foreach($build->items as $item)
                    <li class="text-white mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span>{{ $item->amount }}x {{ $item->item_identifier }}</span>
                            <span id="submitted-amount-{{ $item->id }}">{{ $item->submitted_amount }} / {{ $item->amount }}</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-4">
                            <div id="progress-bar-{{ $item->id }}" class="bg-green-500 h-4 rounded-full" style="width: {{ ($item->submitted_amount / $item->amount) * 100 }}%"></div>
                        </div>
                        <form class="mt-2" onsubmit="submitItem(event, {{ $item->id }})">
                            @csrf
                            <div class="flex items-center">
                                <input type="number" name="amount" class="bg-gray-900 text-white border border-gray-700 rounded-l-md py-1 px-2 w-24" placeholder="Amount">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-r-md">Submit</button>
                            </div>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
    function submitItem(event, itemId) {
        event.preventDefault();
        const form = event.target;
        const amount = form.querySelector('input[name="amount"]').value;

        fetch(`/builds/{{ $build->id }}/items/${itemId}/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const submittedAmountEl = document.getElementById(`submitted-amount-${itemId}`);
                const progressBarEl = document.getElementById(`progress-bar-${itemId}`);
                
                submittedAmountEl.textContent = `${data.submitted_amount} / ${data.required_amount}`;
                progressBarEl.style.width = `${(data.submitted_amount / data.required_amount) * 100}%`;
                form.querySelector('input[name="amount"]').value = '';
            }
        });
    }
</script>
@endsection
