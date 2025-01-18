<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\EditMultipleFilesRequest;
use App\Http\Requests\v1\File\GetDiffRequest;
use App\Http\Requests\v1\File\PushFileUpdateRequest;
use App\Http\Requests\v1\File\StoreUpdateFileRequest;
use App\Services\v1\File\FileService;
use App\Traits\RestTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;

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

    public function show($fileId)
    {
        $file = $this->fileService->view($fileId, ['owner', 'lastVersion', 'directory']);
        if (!$file) {
            abort(404);
        }
        if (auth()->user()->isAdmin()) {
            return Inertia::render('dashboard/admin/groups/directories/Files/Show', [
                'file' => $file,
            ]);
        }
        return Inertia::render('dashboard/customer/Files/Show', [
            'file' => $file
        ]);
    }

    public function getDiff(GetDiffRequest $request)
    {
        $data = $this->fileService->getDiff($request->validated());
        if (!$data) {
            abort(404);
        }
        if (auth()->user()->isAdmin()) {
            return Inertia::render('dashboard/admin/groups/directories/Files/GetDiff', $data);
        }

        return Inertia::render('dashboard/customer/Files/GetDiff', $data);
    }

    public function streamFile(Request $request)
    {
        $filePath = $request->query('path');

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->stream(function () use ($filePath) {
            $stream = fopen($filePath, 'r');
            while (!feof($stream)) {
                echo fread($stream, 8192); // 8 KB chunks
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }

    public function getLastComparison($fileId)
    {
        $file = $this->fileService->view($fileId, ['owner', 'lastVersion', 'directory']);
        if (!$file->last_comparison) {
            abort(404, 'File not found');
        }

        $rendererOptions = [
            'detailLevel' => 'line',
            'language' => 'eng',
            'lineNumbers' => true,
            'separateBlock' => true,
            'showHeader' => true,
            'spacesToNbsp' => false,
            'tabSize' => 4,
            'mergeThreshold' => 0.8,
            'cliColorization' => RendererConstant::CLI_COLOR_ENABLE,
            'outputTagAsString' => false,
            'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
            'wordGlues' => [' ', '-'],
            'resultForIdenticals' => null,
            'wrapperClasses' => ['diff-wrapper'],
        ];
        $htmlRenderer = RendererFactory::make('SideBySide', $rendererOptions);
        $htmlRenderer = trim(preg_replace('/\r+|\n+/', '', $htmlRenderer->renderArray(json_decode($file->last_comparison, true))));

        if (auth()->user()->isAdmin()) {
            return Inertia::render('dashboard/admin/groups/directories/Files/LastComparison', [
                'result' => $htmlRenderer,
            ]);
        }

        return Inertia::render('dashboard/customer/Files/LastComparison', [
            'result' => $htmlRenderer,
        ]);
    }
}
