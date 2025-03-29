<x-base>
    <div class="max-w-7xl mx-auto mt-10">
        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Tasks</h2>
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
