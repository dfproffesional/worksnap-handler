<?php
use Illuminate\Support\Carbon;
?>

<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-3 min-h-[595px]">
        <div class="text-gray-900 dark:text-gray-100">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border sticky t-0">
                    <tr>
                        <th scope="col" class="p-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="p-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Init Work
                        </th>
                        <th scope="col" class="p-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Leave Work
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 cursor-pointer">
                    {{-- {{dd($timeEntries)}} --}}
                    @foreach($timeEntries as $date => $entry)
                        <tr 
                            class="hover:bg-gray-100" 
                            {{-- wire:click="goEmployee(
                                {{request()->route(param: 'id')}},
                                {{$employee->user_id}}, 
                                '{{$employee->user_name}}'
                            )" --}}
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $date }}
                            </td>
                            @php
                                $login = Carbon::createFromTimestamp($entry->logged_timestamp_1)->tz('UTC')->tz('America/New_York');
                                $nineAm = $login->copy()->hour(9)->minute(30)->second(0);
                                $validateAm = $login->gte($nineAm); 
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{$validateAm ? 'bg-red-400 text-white' : 'text-gray-900'}}">
                                {{ $login->toTimeString() }}
                            </td>
                            @php
                                $logout = Carbon::createFromTimestamp($entry->logged_timestamp_2)->tz('UTC')->tz('America/New_York');
                                $svenTenPm = $logout->copy()->hour(17)->minute(30)->second(0);
                                $validatePm = $login->lte($svenTenPm);
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{$validateAm ? 'bg-red-400 text-white' : 'text-gray-900'}}">
                                {{ $logout->toTimeString() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $links !!}
</div>
