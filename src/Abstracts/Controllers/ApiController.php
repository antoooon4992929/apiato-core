<?php

namespace Apiato\Core\Abstracts\Controllers;

use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    public function responseWithTransform(mixed $data, string $transformerClass, int $status = 200): JsonResponse
    {
        $data = fractal($data, new $transformerClass)->toArray();

        return new JsonResponse($data, $status);
    }

    public function responseWithCreatedTransform(mixed $data, string $transformerClass): JsonResponse
    {
        return $this->responseWithTransform($data, $transformerClass, 201);
    }

    public function json($data, $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    public function noContent($status = 204): JsonResponse
    {
        return new JsonResponse(null, $status);
    }
}
