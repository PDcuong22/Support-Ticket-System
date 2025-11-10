<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 lg:p-8">
        {{-- Title --}}
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            {{ $ticket->title }}
        </h1>

        {{-- Meta Info --}}
        <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>{{ $ticket->user->name }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $ticket->created_at->format('M d, Y \a\t H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Updated {{ $ticket->updated_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
            <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">
                {{ $ticket->description }}
            </div>
        </div>

        {{-- Labels --}}
        @if($ticket->labels && $ticket->labels->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Labels</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($ticket->labels as $label)
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full">
                            {{ $label->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Attachments --}}
        @if($ticket->attachments && $ticket->attachments->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Attachments</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($ticket->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->file_path) }}" 
                           target="_blank"
                           class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex-shrink-0">
                                @if(Str::startsWith($attachment->mime_type, 'image/'))
                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $attachment->file_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ number_format($attachment->file_size / 1024, 2) }} KB
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>