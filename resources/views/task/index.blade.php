<x-base>
    <div class="max-w-7xl mx-auto mt-10">
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Tasks</h2>
                    <a href="{{ route('task.create') }}">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Task
                        </button>
                    </a>
                </div>

                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Filter Tasks</h3>
                    <form method="GET" action="{{ route('task.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ $filters['status'] === $status->value ? 'selected' : '' }}>
                                        {{ ucfirst($status->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="priority" name="priority" class="p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="all" {{ $filters['priority'] === 'all' ? 'selected' : '' }}>All Priorities</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority->value }}" {{ $filters['priority'] === $priority->value ? 'selected' : '' }}>
                                        {{ ucfirst($priority->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="due_date_sort" class="block text-sm font-medium text-gray-700 mb-1">Due Date Order</label>
                            <select id="due_date_sort" name="due_date_sort" class="p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="none" {{ ($filters['due_date_sort'] ?? 'none') === 'none' ? 'selected' : '' }}>No Sorting</option>
                                <option value="asc" {{ ($filters['due_date_sort'] ?? 'none') === 'asc' ? 'selected' : '' }}>Earliest First</option>
                                <option value="desc" {{ ($filters['due_date_sort'] ?? 'none') === 'desc' ? 'selected' : '' }}>Latest First</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Apply Filters
                            </button>
                            <a href="{{ route('task.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
                                @if(($filters['due_date_sort'] ?? 'none') === 'asc')
                                    <span class="ml-1">↑</span>
                                @elseif(($filters['due_date_sort'] ?? 'none') === 'desc')
                                    <span class="ml-1">↓</span>
                                @endif
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('task.show', $task->id) }}'">
                                <td class="px-6 py-4 whitespace-nowrap"> {{ $task->name }}</td>
                                @if ($task->status === \App\Enums\Status::TO_DO->value)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $task->status }}
                                </span>
                                    </td>
                                @elseif ($task->status === \App\Enums\Status::IN_PROGRESS->value)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $task->status }}
                                </span>
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $task->status }}
                                </span>
                                    </td>
                                @endif
                                @if ($task->priority === \App\Enums\Priority::LOW->value)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $task->priority }}
                                </span>
                                    </td>
                                @elseif ($task->priority === \App\Enums\Priority::MEDIUM->value)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    {{ $task->priority }}
                                </span>
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs uppercase leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $task->priority }}
                                </span>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap"> {{ $task->due_date }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="font-bold px-6 py-4 whitespace-nowrap">
                                    @if(request()->has('status') || request()->has('priority') || request()->has('due_date_sort'))
                                        No tasks match your filter criteria.
                                    @else
                                        You currently don't have any tasks. Add some
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-base>
