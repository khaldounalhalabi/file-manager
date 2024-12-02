<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\FileLog\StoreUpdateFileLogRequest;
use App\Http\Resources\v1\FileLogResource;
use App\Services\v1\FileLog\FileLogService;
use App\Traits\RestTrait;
use Exception;
use Illuminate\Http\Request;

class FileLogController extends Controller
{
    use RestTrait;

    private FileLogService $fileLogService;

    // place the relations you want to return them within the response
    private array $relations = ['file', 'user'];

    public function __construct()
    {
        $this->fileLogService = FileLogService::make();
    }

    public function getByFile($fileId)
    {
        $items = $this->fileLogService->getByFile($fileId, $this->relations);
        if ($items) {
            return response()->json([
                'data' => FileLogResource::collection($items['data']),
                'pagination_data' => $items['pagination_data'],
            ], 200);
        }

        return response()->json([
            'data' => [],
            'pagination_data' => null,
        ], 200);
    }

    public function export(Request $request)
    {
        $ids = $request->ids ?? array();

        try {
            $result = $this->fileLogService->export($ids);
            session()->flash('success', __('site.success'));
            return $result;
        } catch (Exception) {
            return redirect()->back()->with('error', __('site.something_went_wrong'));
        }
    }
}
