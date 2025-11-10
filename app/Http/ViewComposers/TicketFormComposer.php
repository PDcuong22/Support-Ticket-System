<?php

namespace App\Http\ViewComposers;

use App\Services\LabelService;
use App\Services\CategoryService;
use App\Services\StatusService;
use App\Services\PriorityService;
use Illuminate\View\View;
class TicketFormComposer
{
    protected $labelService;
    protected $categoryService;
    protected $statusService;
    protected $priorityService;

    public function __construct(LabelService $labelService, CategoryService $categoryService, StatusService $statusService, PriorityService $priorityService)
    {
        $this->labelService = $labelService;
        $this->categoryService = $categoryService;
        $this->statusService = $statusService;
        $this->priorityService = $priorityService;
    }

    public function compose(View $view)
    {
        $view->with([
            'labels' => $this->labelService->getAllLabels(),
            'categories' => $this->categoryService->getAllCategories(),
            'statuses' => $this->statusService->getAllStatuses(),
            'priorities' => $this->priorityService->getAllPriorities(),
        ]);
    }
}