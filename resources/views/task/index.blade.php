<x-base>
    <div class="max-w-7xl mx-auto mt-10">
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
                                <td colspan="4" class="font-bold px-6 py-4 whitespace-nowrap">You currently don't have any tasks.
                                    Add some
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
