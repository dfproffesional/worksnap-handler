<?php

namespace App\Livewire\Dashboard;

use App\Services\PaginatorManual;
use App\Services\WorksnapServices;
use Livewire\Component;
use Session;

class EmployeeTimeEntries extends Component
{
    const DEFAULT_SESSION_KEY = 'employee_detail';

    public $links;
    private $pagination;

    public $timeEntries = [];

    public function mount()
    {
        $this->worksnap = WorksnapServices::getInstance();
        $this->defineTimeEntries();
    }

    public function render()
    {
        return view('livewire.dashboard.employee-time-entries');
    }

    private function groupDates($records)
    {
        $groups = [];

        foreach ($records as $record) {
            $date = date('d-m-Y', $record->from_timestamp); // Access the property
            
            if (!isset($groups[$date])) {
                $groups[$date] = (object) [
                    'from' => $date,
                    'logged_timestamp_1' => $record->logged_timestamp, // Access the property
                    'logged_timestamp_2' => $record->logged_timestamp, // Access the property
                ];
            } else {
                if ($record->logged_timestamp < $groups[$date]->logged_timestamp_1) {
                    $groups[$date]->logged_timestamp_1 = $record->logged_timestamp;
                }
                if ($record->logged_timestamp > $groups[$date]->logged_timestamp_2) {
                    $groups[$date]->logged_timestamp_2 = $record->logged_timestamp;
                }
            }
        }

        return $groups;
    }

    private function fetchEntries():mixed
    {
        $today = new \DateTime('first day of this month 00:00:00');
        $lastDay = (clone $today)->modify('+29 days');
        $records = $this->worksnap->request(
            'GET', 
            'projects/'.request()->route('id').'/users/' . request()->route('user_id') . '/time_entries.xml',
            [
                'query' => [
                    'from_timestamp' => $today->getTimestamp(),
                    'to_timestamp' => $lastDay->getTimestamp(),
                    'time_entry_type' => 'online'
                ]
            ]
        );

        return $this->groupDates($records);
    }

    private function defineTimeEntries()
    {
        $this->pagination = PaginatorManual::paginateByArray(
            $this->fetchEntries(), 
            10
        )->withPath(
            '/dashboard/' 
            . request()->route('id') . '/' 
            . request()->route('user_id') 
        )->appends([
            'employee' => request()->query('employee')
        ]);
        $this->timeEntries = $this->pagination->items();
        $this->links = $this->pagination->links()->toHtml();
    }

}
