<?php
if (! function_exists('dataResponse')) {
    /**
     * Make data response
     *
     * @param $message
     * @param int $code
     * @param $data
     * @return array
     */
    function dataResponse($message, int $code, $data): array
    {
        $dataResponse['message'] = $message;
        $dataResponse['code'] = $code;
        $dataResponse['data'] = $data;

        return $dataResponse;
    }
}
