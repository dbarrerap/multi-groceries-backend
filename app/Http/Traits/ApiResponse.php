<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Build a success response.
     *
     * @param string|array $data
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(mixed $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data, 'status' => 'success'], $code);
    }

    /**
     * Build a created response.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function createdResponse(mixed $data): JsonResponse
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    /**
     * Build an error response.
     *
     * @param string|array $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(string|array $message, int $code): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code, 'status' => 'error'], $code);
    }

    /**
     * Build a validation error response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Build a model not found error response.
     *
     * @param string $modelName
     * @return JsonResponse
     */
    public function modelNotFoundResponse(string $modelName): JsonResponse
    {
        return $this->errorResponse("{$modelName} not found", Response::HTTP_NOT_FOUND);
    }
}
