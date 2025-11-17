<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Ticket') }}
        </h2>
    </x-slot>

    {{-- Page Title --}}
    <x-slot name="title">
        Create Ticket - Support System
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Use shared fields partial (handles both create/edit) --}}
                        @include('tickets.partials._fields', [
                            // for create there is no $ticket, partial sẽ dùng null-safe access
                            'showAssigned' => (Auth::check() && optional(Auth::user()->role)->name === 'Admin')
                        ])

                        {{-- File Upload --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">
                                Attachments
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-semibold">Drag & Drop your files or</span>
                                            <span class="text-purple-600 underline">Browse</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, PDF up to 10MB</p>
                                    </div>
                                    <input id="dropzone-file" type="file" name="attachments[]" multiple class="hidden" 
                                           accept="image/*,.pdf,.doc,.docx,.txt" />
                                </label>
                            </div>
                            <div id="file-list" class="mt-3 text-sm text-gray-600"></div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                    class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-8 py-3 transition">
                                Submit
                            </button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Scripts --}}
    <x-slot name="scripts">
        <script>
            // File upload preview
            const fileInput = document.getElementById('dropzone-file');
            const fileList = document.getElementById('file-list');

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    fileList.innerHTML = '';
                    if (this.files.length > 0) {
                        const ul = document.createElement('ul');
                        ul.className = 'space-y-1';
                        
                        Array.from(this.files).forEach(file => {
                            const li = document.createElement('li');
                            li.className = 'flex items-center gap-2';
                            li.innerHTML = `
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"></path>
                                </svg>
                                <span>${file.name} <span class="text-gray-400">(${(file.size / 1024).toFixed(2)} KB)</span></span>
                            `;
                            ul.appendChild(li);
                        });
                        
                        fileList.appendChild(ul);
                    }
                });

                // Drag and drop
                const dropArea = fileInput.closest('label');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, () => {
                        dropArea.classList.add('border-purple-500', 'bg-purple-50');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, () => {
                        dropArea.classList.remove('border-purple-500', 'bg-purple-50');
                    });
                });

                dropArea.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;
                    
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                });
            }
        </script>
    </x-slot>
</x-app-layout>