<?php

namespace App\Livewire\Dashboard;

use App\Services\PaginatorManual;
use App\Services\WorksnapServices;
use Livewire\Component;

class ProjectDetail extends Component
{
    const DEFAULT_SESSION_KEY = 'project_detail';

    public $links;
    private $worksnap;
    private $pagination;
    public $employees = [];


    public function mount()
    {
        $this->worksnap = WorksnapServices::getInstance();
        $this->defineEmployees();
    }

    public function render()
    {
        return view('livewire.dashboard.project-detail');
    }

    public function goEmployee($projectId, $userId, $name)
    {
        return redirect("/dashboard/$projectId/$userId?employee=$name");
    }

    private function fetchProject()
    {
        return $this->worksnap->request('GET', 'projects/'.request()->route('id').'/user_assignments.xml');
    }

    private function defineEmployees()
    {
        $this->pagination = PaginatorManual::paginateByArray(
            $this->fetchProject(), 
            10
        )->withPath(
            '/dashboard/' 
            . request()->route('id')
        )->appends([
            'project_name' => request()->get('project_name')
        ]);
        $this->employees = array_filter($this->pagination->items(), fn($employee) => is_string($employee->user_name));
        $this->links = $this->pagination->links()->toHtml();
    }
}
