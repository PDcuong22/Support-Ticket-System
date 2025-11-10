<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 lg:p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
            Comments ({{ $ticket->comments->count() }})
        </h3>
        
        {{-- Comment Form --}}
        <form action="{{ route('comments.store', $ticket) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <textarea 
                        name="content" 
                        rows="3" 
                        placeholder="Add a comment..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none @error('content') border-red-500 @enderror"
                        required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2 flex justify-end">
                        <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-6 py-2 rounded-lg transition">
                            Post Comment
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Comments List --}}
        @if($ticket->comments->count() > 0)
            <div class="space-y-4">
                @foreach($ticket->comments->sortByDesc('created_at') as $comment)
                    <div class="flex gap-4 group">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-semibold text-sm">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-lg p-4 relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                        @if($comment->user_id === $ticket->user_id)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">Author</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        @if($comment->user_id === Auth::id())
                                            <form action="{{ route('comments.destroy', [$ticket, $comment]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                                  class="opacity-0 group-hover:opacity-100 transition">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-gray-500">No comments yet. Be the first to comment!</p>
            </div>
        @endif
    </div>
</div>