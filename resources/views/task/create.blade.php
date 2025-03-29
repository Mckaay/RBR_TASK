<x-base>
    <div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('task.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Tasks
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-lg font-medium leading-6 text-gray-900">Add New Task</h1>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Fill in the details to create a new task.</p>
            </div>

            <div class="border-t border-gray-200">
                <form action="{{ route('task.store') }}" method="POST" class="px-4 py-5 sm:p-6">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Task Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror" placeholder="Enter task name" required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" class="p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror" placeholder="Enter task description">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status') border-red-500 @enderror" required>
                                <option value="">Select status</option>
                                <option value="{{ \App\Enums\Status::TO_DO->value }}" {{ old('status') == \App\Enums\Status::TO_DO->value ? 'selected' : '' }}>To Do</option>
                                <option value="{{ \App\Enums\Status::IN_PROGRESS->value }}" {{ old('status') == \App\Enums\Status::IN_PROGRESS->value ? 'selected' : '' }}>In Progress</option>
                                <option value="{{ \App\Enums\Status::DONE->value }}" {{ old('status') == \App\Enums\Status::DONE->value ? 'selected' : '' }}>Done</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                            <select id="priority" name="priority" class="p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('priority') border-red-500 @enderror" required>
                                <option value="">Select priority</option>
                                <option value="{{ \App\Enums\Priority::LOW->value }}" {{ old('priority') == \App\Enums\Priority::LOW->value ? 'selected' : '' }}>Low</option>
                                <option value="{{ \App\Enums\Priority::MEDIUM->value }}" {{ old('priority') == \App\Enums\Priority::MEDIUM->value ? 'selected' : '' }}>Medium</option>
                                <option value="{{ \App\Enums\Priority::HIGH->value }}" {{ old('priority') == \App\Enums\Priority::HIGH->value ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('due_date') border-red-500 @enderror" required>
                        @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-5 border-t border-gray-200">
                        <div class="flex justify-end">
                            <a href="{{ route('task.index') }}">
                                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </button>
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-base>
