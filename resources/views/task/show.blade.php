<x-base>
    @if (session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="/" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Tasks
            </a>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->name }}</h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Created on {{ $task->created_at }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('task.edit', $task->id) }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('task.share.create', $task->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-share-alt mr-2"></i> Share
                        </button>
                    </form>
                    <form action="{{ route('task.delete', $task->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($task->status === \App\Enums\Status::TO_DO->value)
                                <span
                                    class="px-2 inline-flex uppercase text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
{{ $task->status }}
</span>
                            @elseif ($task->status === \App\Enums\Status::IN_PROGRESS->value)
                                <span
                                    class="px-2 inline-flex uppercase text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
{{ $task->status }}
</span>
                            @else
                                <span
                                    class="px-2 inline-flex uppercase text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
{{ $task->status }}
</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <p class="text-sm font-medium text-gray-500">Priority</p>
                        <p class="mt-1 text-sm uppercase text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($task->priority === \App\Enums\Priority::LOW->value)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $task->priority }}
                        </span>
                            @elseif ($task->priority === \App\Enums\Priority::MEDIUM->value)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            {{ $task->priority }}
                        </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $task->priority }}
                        </span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <p class="text-sm font-medium text-gray-500">Due date</p>
                        <p class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $task->due_date }}</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <p class="text-sm font-medium text-gray-500">Description</p>
                        <p class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"> {{ $task->description }}</p>
                    </div>
                </dl>
            </div>
        </div>

        @if (session('share_url'))
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h2 class="text-lg font-medium text-gray-900">Share Link</h2>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        This link will expire in 7 days
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="flex items-center">
                        <input type="text" readonly value="{{ session('share_url') }}"
                               class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               id="share-url">
                        <button onclick="copyShareUrl()"
                                class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-copy mr-2"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function copyShareUrl() {
                    const shareUrl = document.getElementById('share-url');
                    shareUrl.select();
                    document.execCommand('copy');
                    alert('Share link copied to clipboard!');
                }
            </script>
        @endif
    </div>

</x-base>
