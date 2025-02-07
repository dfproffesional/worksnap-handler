<?php

namespace App\Livewire\Dashboard;

use App\Services\PaginatorManual;
use App\Services\WorksnapServices;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProjectItems
{
    public int $id;
    public string $name;
    public string $status;
    public string $by_hour;
    public string $description;
}

/**
 * @property LengthAwarePaginator $pagination
 * @property list<ProjectItems> $projects
 * @property WorksnapServices $worksnap
 * @property mixed  $links
 */
class ProjectsTable extends Component
{
    const DEFAULT_SESSION_KEY = 'projects_table';

    public $projects = [];
    public $links;

    private $pagination;
    private $worksnap;

    public function mount()
    {
        $this->worksnap = WorksnapServices::getInstance();
        $this->defineProjects();
    }

    public function render()
    {
        return view('livewire.dashboard.projects-table');
    }

    public function goProject($id, $name)
    {
        return redirect('/dashboard' . "/$id?project_name=$name");
    }

    /**
     * @return list<ProjectItems>|mixed
     */
    private function fetchProjects(): mixed
    {
        return $this->worksnap->request('GET', 'projects.xml');
    }

    /**
     * Define the projects and store them in the session if not already present.
     *
     * @return void
     */
    private function defineProjects()
    {
        if (!$this->validateSessionItems()){
            Session::put(self::DEFAULT_SESSION_KEY, (object) [
                'projects' => $this->fetchProjects()
            ]);
        }

        $this->pagination = PaginatorManual::paginateByArray(
            Session::get(self::DEFAULT_SESSION_KEY)->projects, 
            10
        )->withPath('/dashboard');

        $this->projects = $this->pagination->items();
        $this->links = $this->pagination->links()->toHtml();
    }

    /**
     * Validate if the session contains the projects table and project items.
     *
     * @return bool
     */
    private function validateSessionItems()
    {
        $sessionProjects = Session::get(self::DEFAULT_SESSION_KEY);
        return Session::exists(self::DEFAULT_SESSION_KEY) 
            && isset($sessionProjects->projects) 
            && count($sessionProjects->projects) > 0;
    }
}
