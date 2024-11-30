<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\FileVersion\FileVersionService;

class FileVersionController extends Controller
{
    private FileVersionService $fileVersionService;

    // place the relations you want to return them within the response
    private array $relations = ['file'];

    public function __construct()
    {
        $this->fileVersionService = FileVersionService::make();
    }

    public function getByFile($fileId)
    {
        $data = $this->fileVersionService->getByFile($fileId, $this->relations);
        if ($data) {
            return response()->json(array(
                'data' => $data['data'],
                'pagination_data' => $data['pagination_data'],
            ));
        }
        
        return response()->json(array(
            'data' => array(),
            'pagination_data' => null,
        ));
    }
}
