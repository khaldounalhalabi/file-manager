<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Directory\StoreUpdateDirectoryRequest;
use App\Http\Resources\DirectoryResource;
use App\Services\v1\Directory\DirectoryService;
use App\Traits\RestTrait;
use Inertia\Inertia;

class DirectoryController extends Controller
{
    use RestTrait;

    private DirectoryService $directoryService;

    // place the relations you want to return them within the response
    private array $relations = ['group'];

    public function __construct()
    {
        $this->directoryService = DirectoryService::make();
    }

    public function getRoot()
    {
        $directories = $this->directoryService->getRoot($this->relations);
        return $this->apiResponse(DirectoryResource::collection($directories['data']), ApiController::STATUS_OK, __('site.get_successfully'), $directories['pagination_data']);
    }

    public function store(StoreUpdateDirectoryRequest $request)
    {
        $directory = $this->directoryService->store($request->validated());
        if ($directory) {
            return redirect()->back()->with('success', __('site.stored_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function update(StoreUpdateDirectoryRequest $request, $directoryId)
    {
        $directory = $this->directoryService->update($request->validated(), $directoryId);
        if ($directory) {
            return redirect()->back()->with('success', __('site.update_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function destroy($directoryId)
    {
        $result = $this->directoryService->delete($directoryId);
        if ($result) {
            return $this->apiResponse(true, ApiController::STATUS_OK, __('site.delete_successfully'));
        }
        return $this->noData(false);
    }

    public function show($directoryId)
    {
        $directory = $this->directoryService->view($directoryId, ['subDirectories', 'files', 'files.lastVersion', 'files.lastLog']);
        if ($directory) {
            return Inertia::render('dashboard/customer/directories/Show', [
                'directory' => $directory,
            ]);
        }

        abort(404);
    }
}
