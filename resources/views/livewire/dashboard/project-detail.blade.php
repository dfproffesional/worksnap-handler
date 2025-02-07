<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-3 min-h-[595px]">
        <div class="text-gray-900 dark:text-gray-100">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border sticky t-0">
                    <tr>
                        {{-- "id":"318411","project_id":"76558","user_id":"1504","role":"Manager","flag_allow_logging_time":"1","window_for_deleting_time":"-1","window_for_adding_offline_time":"-1","user_first_name":"Enrique","user_last_name":"Teran","user_name":"Enrique Teran","user_email":"ejteran@gmail.com","hourly_rate":"0.00" --}}
                        <th scope="col" class="p-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employee
                        </th>
                        <th scope="col" class="p-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="p-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Roles
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 cursor-pointer">
                    @foreach($employees as $employee)
                        <tr 
                            class="hover:bg-gray-100" 
                            wire:click="goEmployee(
                                {{request()->route(param: 'id')}},
                                {{$employee->user_id}}, 
                                '{{$employee->user_name}}'
                            )"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $employee->user_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $employee->user_email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $employee->role }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $links !!}
</div>
