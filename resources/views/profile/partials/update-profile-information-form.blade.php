<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-update-form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo (Max 8MB)')" />

            <div class="mt-2 flex items-center gap-4">
                <div id="photo-preview-container" class="relative">
                    @if ($user->profile_photo)
                        <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="h-16 w-16 rounded-full object-cover shadow-sm border border-gray-200">
                    @else
                        <div id="photo-preview-placeholder" class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl border border-gray-300">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <img id="photo-preview" src="" alt="Profile Photo" class="h-16 w-16 rounded-full object-cover shadow-sm border border-gray-200 hidden">
                    @endif
                </div>

                <input id="profile_photo_input" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" accept="image/*" />
                <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <!-- Cropper Modal -->
    <div id="cropper-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden flex flex-col">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Crop Profile Photo</h3>
                <button type="button" id="close-cropper" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 flex-grow relative bg-gray-100 flex items-center justify-center" style="min-height: 300px; max-height: 60vh;">
                <img id="image-to-crop" src="" alt="Image to crop" class="max-w-full max-h-full block">
            </div>
            <div class="p-4 border-t flex justify-end gap-3 bg-gray-50">
                <button type="button" id="cancel-crop" class="px-4 py-2 bg-gray-200 text-gray-700 rounded font-medium hover:bg-gray-300 transition">Cancel</button>
                <button type="button" id="apply-crop" class="px-4 py-2 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 transition">Crop & Apply</button>
            </div>
        </div>
    </div>

    <!-- Include Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('profile_photo_input');
            const hiddenInput = document.getElementById('profile_photo');
            const imageToCrop = document.getElementById('image-to-crop');
            const cropperModal = document.getElementById('cropper-modal');
            const applyCropBtn = document.getElementById('apply-crop');
            const cancelCropBtn = document.getElementById('cancel-crop');
            const closeCropperBtn = document.getElementById('close-cropper');
            const photoPreview = document.getElementById('photo-preview');
            const photoPlaceholder = document.getElementById('photo-preview-placeholder');

            let cropper = null;

            // Handle file selection
            input.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Please select an image file.');
                        input.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imageToCrop.src = event.target.result;
                        cropperModal.classList.remove('hidden');

                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1, // Force 1:1 ratio
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.8,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle crop apply
            applyCropBtn.addEventListener('click', function() {
                if (!cropper) return;

                // Get cropped canvas
                const canvas = cropper.getCroppedCanvas({
                    width: 400, // Compress/resize to reasonable dimensions
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                // Convert canvas to blob (compressed JPEG)
                canvas.toBlob(function(blob) {
                    // Create a new File object from the blob
                    const file = new File([blob], "profile_photo.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });

                    // Update hidden input using DataTransfer
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    hiddenInput.files = dataTransfer.files;

                    // Update preview
                    const previewUrl = URL.createObjectURL(blob);
                    photoPreview.src = previewUrl;
                    photoPreview.classList.remove('hidden');
                    if (photoPlaceholder) photoPlaceholder.classList.add('hidden');

                    // Close modal
                    closeModal();
                }, 'image/jpeg', 0.85); // 0.85 quality compression
            });

            function closeModal() {
                cropperModal.classList.add('hidden');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                // Don't reset visible input so user sees the file name, but we use hiddenInput for actual upload
            }

            cancelCropBtn.addEventListener('click', function() {
                closeModal();
                input.value = ''; // Reset input if cancelled
            });

            closeCropperBtn.addEventListener('click', function() {
                closeModal();
                input.value = '';
            });
        });
    </script>
</section>
