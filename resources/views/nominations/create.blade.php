@extends('main')

@section('title', 'Nominate')

@section('content')
<div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-center">Nominate</h1>
    <p class="text-gray-400 mb-8 text-center">Nominate someone for a category.</p>

    @if($processStatus && $processStatus->status == 'nominating')
        <div class="text-center mb-4">
            <p class="text-xl font-semibold">Nomination ends in:</p>
            <div id="nomination-timer" class="text-2xl font-bold"></div>
        </div>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($categories as $category)
                <div class="category-card bg-gray-700 border border-gray-600 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-all duration-200"
                     data-category-id="{{ $category->id }}"
                     data-category-name="{{ $category->name }}"
                     data-category-description="{{ $category->description }}"
                     data-category-allow-image-uploads="{{ $category->allow_image_uploads ? 'true' : 'false' }}">
                    <h3 class="text-lg font-semibold text-white">{{ $category->name }}</h3>
                    <p class="text-gray-400 text-sm">{{ $category->description }}</p>
                </div>
            @endforeach
        </div>

        <!-- Nomination Modal -->
        <div id="nomination-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md mx-auto">
                <h2 class="text-2xl font-bold mb-4 text-white" id="modal-category-name"></h2>
                <p class="text-gray-400 mb-4" id="modal-category-description"></p>
                <form action="{{ route('nominations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" id="modal-category-id">
                    <div class="mb-4">
                        <label for="nominee_name" class="block mb-2 text-sm font-medium text-white">Nominee Name</label>
                        <input type="text" name="name" id="nominee_name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div class="mb-6">
                        <label for="nomination_description" class="block mb-2 text-sm font-medium text-white">Description</label>
                        <textarea name="description" id="nomination_description" rows="4" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required></textarea>
                    </div>

                    <!-- Image Upload Fields (initially hidden) -->
                    <div id="image-upload-fields" class="hidden mb-6">
                        <label class="block mb-2 text-sm font-medium text-white">Upload Images (up to 3)</label>
                        <input type="file" name="images[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2">
                        <input type="file" name="images[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2">
                        <input type="file" name="images[]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="close-modal" class="button button--secondary mr-4">Cancel</button>
                        <button type="submit" class="button button--primary">Submit Nomination</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="text-center text-xl font-semibold text-gray-300">
            Nominations are not currently open. Please check back later.
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nominationTimerElement = document.getElementById('nomination-timer');
        const processStatus = @json($processStatus);
        const nominationEndsAt = processStatus ? new Date(processStatus.nomination_ends_at).getTime() : null;

        if (nominationTimerElement && nominationEndsAt) {
            const updateTimer = setInterval(() => {
                const now = Date.now();
                const distance = nominationEndsAt - now;

                if (distance <= 0) {
                    clearInterval(updateTimer);
                    nominationTimerElement.textContent = "NOMINATION CLOSED";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                nominationTimerElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }, 1000);
        }

        // Modal Logic
        const nominationModal = document.getElementById('nomination-modal');
        const closeModalButton = document.getElementById('close-modal');
        const modalCategoryId = document.getElementById('modal-category-id');
        const modalCategoryName = document.getElementById('modal-category-name');
        const modalCategoryDescription = document.getElementById('modal-category-description');
        const nomineeNameInput = document.getElementById('nominee_name');
        const nominationDescriptionInput = document.getElementById('nomination_description');
        const imageUploadFields = document.getElementById('image-upload-fields'); // New line

        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const categoryId = this.dataset.categoryId;
                const categoryName = this.dataset.categoryName;
                const categoryDescription = this.dataset.categoryDescription;
                const categoryAllowImageUploads = this.dataset.categoryAllowImageUploads === 'true'; // New line

                modalCategoryId.value = categoryId;
                modalCategoryName.textContent = `Nominate for: ${categoryName}`;
                modalCategoryDescription.textContent = categoryDescription || 'No description provided.';
                
                // Toggle image upload fields visibility
                if (categoryAllowImageUploads) {
                    imageUploadFields.classList.remove('hidden');
                } else {
                    imageUploadFields.classList.add('hidden');
                }

                nominationModal.classList.remove('hidden');
                nominationModal.style.display = 'flex';
            });
        });

        closeModalButton.addEventListener('click', function() {
            nominationModal.classList.add('hidden');
            nominationModal.style.display = 'none';
            // Clear form fields when closing modal
            nomineeNameInput.value = '';
            nominationDescriptionInput.value = '';
            // Clear image input fields
            Array.from(imageUploadFields.querySelectorAll('input[type="file"]')).forEach(input => input.value = '');
        });

        // Close modal if clicked outside
        nominationModal.addEventListener('click', function(e) {
            if (e.target === nominationModal) {
                nominationModal.classList.add('hidden');
                nominationModal.style.display = 'none';
                nomineeNameInput.value = '';
                nominationDescriptionInput.value = '';
                // Clear image input fields
                Array.from(imageUploadFields.querySelectorAll('input[type="file"]')).forEach(input => input.value = '');
            }
        });
    });
</script>
@endsection