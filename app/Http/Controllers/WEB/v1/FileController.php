<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\StoreUpdateFileRequest;
use App\Models\File;
use App\Services\v1\File\FileService;
use Inertia\Inertia;

class FileController extends Controller
{

    private $fileService;

    // place the relations you want to return them within the response
    private array $relations = ['group', 'directory'];

    public function __construct()
    {
        $this->fileService = FileService::make();
    }

    public function data()
    {
        $items = $this->fileService->indexWithPagination($this->relations);
        if ($items) {
            return response()->json([
                'data' => $items['data'],
                'pagination_data' => $items['pagination_data'],
            ], 200);
        }

        return response()->json([
            'data' => [],
            'pagination_data' => null,
        ], 200);
    }

    public function index()
    {
        $exportables = File::getModel()->exportable();
        return Inertia::render('dashboard/files/Index', [
            "exportables" => $exportables
        ]);
    }

    public function show($fileId)
    {
        $file = $this->fileService->view($fileId, $this->relations);
        return Inertia::render('dashboard/files/Show', [
            'file' => $file,
        ]);
    }

    public function create()
    {
        return Inertia::render('dashboard/files/Create');
    }

    public function store(StoreUpdateFileRequest $request)
    {
        /** @var File|null $item */
        $file = $this->fileService->store($request->validated(), $this->relations);
        if ($file) {
            return redirect()->route('v1.web.customer.files.index')->with('success', __('site.stored_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function edit($fileId)
    {
        $file = $this->fileService->view($fileId, $this->relations);

        if (!$file) {
            abort(404);
        }
        return Inertia::render('dashboard/files/Edit', [
            'file' => $file
        ]);
    }

    public function update(StoreUpdateFileRequest $request, $fileId)
    {
        /** @var File|null $item */
        $file = $this->fileService->update($request->validated(), $fileId, $this->relations);
        if ($file) {
            return redirect()->route('v1.web.customer.files.index')->with('success', __('site.update_successfully'));
        } else return redirect()->back()->with('error', __('site.there_is_no_data'));
    }

    public function destroy($fileId)
    {
        $result = $this->fileService->delete($fileId);

        if ($result) {
            return response()->json(['success' => __("site.delete_successfully")], 200);
        }

        return response()->json(['error' => __('site.there_is_no_data')], 404);
    }
}
