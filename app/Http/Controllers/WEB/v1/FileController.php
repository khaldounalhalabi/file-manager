<?php

namespace App\Http\Controllers\WEB\v1;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\EditMultipleFilesRequest;
use App\Http\Requests\v1\File\PushFileUpdateRequest;
use App\Http\Requests\v1\File\StoreUpdateFileRequest;
use App\Services\v1\File\FileService;
use App\Traits\RestTrait;

class FileController extends Controller
{
    use RestTrait;

    private FileService $fileService;
    private array $relations = ['group', 'directory'];

    public function __construct()
    {
        $this->fileService = FileService::make();
    }

    public function store(StoreUpdateFileRequest $request)
    {
        $file = $this->fileService->store($request->validated());
        if ($file) {
            return redirect()->back()->with('success', __('site.stored_successfully'));
        }

        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function destroy($fileId)
    {
        $result = $this->fileService->delete($fileId);
        if ($result) {
            return $this->apiResponse(true, ApiController::STATUS_OK, __('site.delete_successfully'));
        }
        return $this->noData(false);
    }

    public function edit($fileId)
    {
        $filePath = $this->fileService->edit($fileId);
        if ($filePath) {
            return $this->apiResponse($filePath, ApiController::STATUS_OK, __('site.update_successfully'), $filePath);
        }

        return $this->noData(false);
    }

    public function pushUpdates(PushFileUpdateRequest $request)
    {
        $result = $this->fileService->pushUpdates($request->validated());
        if ($result) {
            return redirect()->back()->with('success', __('site.update_successfully'));
        } else {
            return redirect()->back()->with('error', __('site.wrong_uploader'));
        }
    }

    public function editMultipleFiles(EditMultipleFilesRequest $request)
    {
        $url = $this->fileService->zipMultipleFiles($request->validated());
        if ($url) {
            return $this->apiResponse([
                'url' => $url,
            ], ApiController::STATUS_OK, __('site.get_successfully'));
        }

        return $this->noData();
    }
}
