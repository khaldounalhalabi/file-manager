<?php

namespace App\Traits;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\ArrayShape;

trait RestTrait
{
    /**
     * this function will determine the api response structure to make all responses has the same structure
     * @param null $data
     * @param null $message
     * @param null $paginate
     */
    public function apiResponse($data = null, int $code = 200, $message = null, $paginate = null): JsonResponse
    {
        $arrayResponse = [
            'data' => $data,
            'status' => $code == 200 || $code == 201 || $code == 204 || $code == 205,
            'message' => $message,
            'code' => $code,
            'pagination_data' => $paginate,
        ];

        return response()->json($arrayResponse, $code, [], JSON_PRETTY_PRINT);
    }

    /**
     * to handle validations
     */
    public function apiValidation($request, $array): JsonResponse|array
    {
        $validator = Validator::make($request->all(), $array);
        if ($validator->fails()) {
            $msg = [
                'text' => 'the given data is invalid',
                'errors' => $validator->errors(),
            ];

            return $this->apiResponse(null, ApiController::STATUS_VALIDATION, $msg);
        }

        return $validator->valid();
    }

    /**
     * standard for pagination
     */
    public function formatPaginateData($data): array
    {
        $paginated_arr = $data->toArray();

        return [
            'currentPage' => $paginated_arr['current_page'],
            'from' => $paginated_arr['from'],
            'to' => $paginated_arr['to'],
            'total' => $paginated_arr['total'],
            'per_page' => $paginated_arr['per_page'],
            'total_pages' => ceil($paginated_arr['total'] / $paginated_arr['per_page']),
            'is_first' => $paginated_arr['current_page'] == 1,
            'is_last' => $paginated_arr['current_page'] == ceil($paginated_arr['total'] / $paginated_arr['per_page']),
        ];
    }

    /**
     * @param mixed $response
     * @return JsonResponse
     */
    public function noData(mixed $response = null): JsonResponse
    {
        return $this->apiResponse($response, ApiController::STATUS_NOT_FOUND, __('site.there_is_no_data'));
    }
}
