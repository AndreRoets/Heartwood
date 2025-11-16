@extends('main')

@section('title', 'Submit a Build')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-white mb-4">Submit a New Build</h1>

    <form action="{{ route('builds.store') }}" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-white text-sm font-bold mb-2">Build Name:</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-white text-sm font-bold mb-2">Description:</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="coordinates" class="block text-white text-sm font-bold mb-2">Coordinates (X, Y, Z):</label>
            <input type="text" name="coordinates" id="coordinates" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-white mb-2">Required Items</h2>
            <div id="items-container">
                <!-- Item inputs will be added here dynamically -->
            </div>
            <button type="button" id="add-item-btn" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Item
            </button>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Submit Build
        </button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item-btn');
    let itemIndex = 0;

    addItemBtn.addEventListener('click', function() {
        const itemHtml = `
            <div class="flex items-center mb-2">
                <input type="text" name="items[${itemIndex}][identifier]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" placeholder="Item Name" required>
                <input type="number" name="items[${itemIndex}][amount]" class="shadow appearance-none border rounded w-24 py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="1" value="1" placeholder="Amount">
                <button type="button" class="ml-2 text-red-500 remove-item-btn">&times;</button>
            </div>
        `;
        itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
        itemIndex++;
    });

    itemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.parentElement.remove();
        }
    });
});
</script>
@endpush
@endsection
