<div class="mt-6">
    <h4 class="text-md font-medium text-gray-800 mb-3">Current Attachments</h4>
    @if($ticket->attachments && $ticket->attachments->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($ticket->attachments as $attachment)
        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <a href="{{ Storage::url($attachment->file_path) }}"
                target="_blank"
                class="flex items-center gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0">
                    @if(Str::startsWith($attachment->mime_type, 'image/'))
                    <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                    @else
                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment->file_name }}">
                        {{ $attachment->file_name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ number_format($attachment->file_size / 1024, 2) }} KB
                    </p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-sm text-gray-500">This ticket has no attachments.</p>
    @endif
</div>