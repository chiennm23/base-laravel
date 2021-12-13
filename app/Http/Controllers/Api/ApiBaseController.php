<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiBaseController extends Controller
{
    /**
     * make api response success
     *
     * @param null $message
     * @param int $code
     * @param null $data
     * @param false $showMessage
     * @return JsonResponse
     */
    public function apiResponseSuccess(
        $message = null,
        int $code = Response::HTTP_OK,
        $data = null,
        bool $showMessage = false
    ): JsonResponse {
        $message = !is_null($message) ? $message : Response::$statusTexts[$code];
        $dataResponse = dataResponse($message, $code, $data);

        if ($showMessage) {
            $dataResponse['showMessage'] = $showMessage;
        }

        return response()->json($dataResponse, $code);
    }

    /**
     * make api response error
     *
     * @param array $errors
     * @param int $code
     * @param bool $showMessage
     * @return JsonResponse
     */
    public function apiResponseError(
        array $errors = [],
        int   $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        bool  $showMessage = false
    ): JsonResponse {
        $dataResponse = dataResponse($errors, $code, null);

        if ($showMessage) {
            $dataResponse['showMessage'] = $showMessage;
        }

        return response()->json($dataResponse, $code);
    }

    /**
     * make api response not found
     *
     * @param string|null $message
     * @param bool $showMessage
     * @return JsonResponse
     */
    public function apiResponseNotFound(string $message = null, bool $showMessage = false): JsonResponse
    {
        $message = $message ?? 'Not Found';
        $code = Response::HTTP_NOT_FOUND;

        $dataResponse = dataResponse($message, $code, null);
        if ($showMessage) {
            $dataResponse['showMessage'] = $showMessage;
        }

        return response()->json($dataResponse, $code);
    }
}
