<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusResponse;

trait HasApiResponse
{
    public function toJsonResponse(string $message, int $status, $data = null): JsonResponse
    {
        $isSuccessful = $status >= 100 && $status < 400;

        $response = ["status" => $isSuccessful ? 'success' : 'error', 'message' => $message];

        if (!empty($data)) {
            $response[$isSuccessful ? 'data' : 'error'] = $data;
        }

        if ($data instanceof JsonResponse) {
            $response = array_merge($response, (new JsonResponse())->setData($data)->getData(true));
        }

        return new JsonResponse($response, $status);
    }

    public function serverErrorResponse(string $message, int $status = 500, ?Throwable $exception = null): JsonResponse
    {
        if (null !== $exception) {
            Log::error("{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}");
            report($exception);

            $response['exception'] = $exception->getMessage();
        }

        return $this->toJsonResponse($message, $status, $response ?? null);
    }

    /**
     * Set the validation error response alert.
     *
     * @param $data
     *
     * @return JsonResponse
     */
    public function formValidationErrorResponse($data = null): JsonResponse
    {
        return $this->toJsonResponse("Validation error", StatusResponse::HTTP_UNPROCESSABLE_ENTITY, $data);
    }

    /**
     * Set the not found response alert.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function notFoundResponse($message, $data = null): JsonResponse
    {
        return $this->toJsonResponse($message, StatusResponse::HTTP_NOT_FOUND, $data);
    }

    /**
     * Set the success response alert.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function successResponse($message, $data = null): JsonResponse
    {
        return $this->toJsonResponse($message, StatusResponse::HTTP_OK, $data);
    }

    /**
     * Set the created resource response alert.
     *
     * @param string $message
     * @param null $data
     *
     * @return JsonResponse
     */
    public function createdResponse(string $message, $data = null): JsonResponse
    {
        return $this->toJsonResponse($message, StatusResponse::HTTP_CREATED, $data);
    }

    /**
     * Set the updated resource response alert.
     *
     * @param string $message
     * @param null $data
     *
     * @return JsonResponse
     */
    public function forbiddenRequestResponse(string $message, $data = null): JsonResponse
    {
        return $this->toJsonResponse($message, StatusResponse::HTTP_FORBIDDEN, $data);
    }

    /**
     * Set the bad request response alert.
     *
     * @param string $message
     * @param null $data
     *
     * @return JsonResponse
     */
    public function badRequestAlert(string $message, $code = StatusResponse::HTTP_BAD_REQUEST, $data = null): JsonResponse
    {
        return $this->toJsonResponse($message, $code, $data);
    }
}

