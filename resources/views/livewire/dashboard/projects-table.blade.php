<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-3 min-h-[595px]">
        <div class="text-gray-900 dark:text-gray-100">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border sticky t-0">
                    <tr>
                        <th scope="col" class="p-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Proyect Name
                        </th>
                        <th scope="col" class="p-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 cursor-pointer">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-100" wire:click="goProject({{$project->id}}, '{{$project->name}}')">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $project->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $project->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $links !!}
</div>


