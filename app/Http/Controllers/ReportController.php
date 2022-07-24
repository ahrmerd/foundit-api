<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{

    private $repo;

    public function __construct(ReportRepository $repo)
    {
        // $this->authorizeResource(Report::class, 'report');
        $this->repo = $repo;
    }


    public function index()
    {
        $this->authorize('viewAny', Report::class);
        return $this->repo->index();
    }

    public function store(StoreReportRequest $request)
    {
        return $this->repo->create(array_merge($request->only(['title', 'description', 'status', 'type']), ['user_id' => auth()->id()]));
    }

    public function show($id)
    {
        $this->authorize('view', Report::findOrFail($id));
        return $this->repo->getById($id);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $this->authorize('update', $report);
        return $report->update($request->only(['title', 'description', 'status', 'type']));
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        return $report->delete();
    }
}
