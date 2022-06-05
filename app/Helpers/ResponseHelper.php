<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * @param int $statusCode
     * @param mixed $data
     * @param string|null $statusMessage
     * @param array|null $additions
     * @param bool $bulk
     * @return JsonResponse
     */
    public static function createResponseFromBase(int    $statusCode,
                                                  mixed  $data = [],
                                                  string $statusMessage = null,
                                                  array  $additions = null,
                                                  bool $bulk = false): JsonResponse
    {
        $headers = ['Content-Language' => app()->getLocale()];

        if (!$bulk) {
            if ($statusCode >= 200 && $statusCode < 300 && $statusCode !== 204) {
                return response()->json(
                    data: self::handle(
                        data: $data,
                        statusCode: $statusCode,
                        statusMessage: $statusMessage,
                        additions: $additions
                    ),
                    status: $statusCode,
                    headers: $headers
                );
            } elseif ($statusCode >= 400 && $statusCode < 500) {
                return response()->json(
                    data: self::handle(
                        errors: $data,
                        statusCode: $statusCode,
                        statusMessage: $statusMessage,
                        additions: $additions
                    ),
                    status: $statusCode,
                    headers: $headers
                );
            } elseif ($statusCode === 204) {
                return self::noContent();
            } else {
                return response()->json(
                    data: self::handle(
                        statusCode: $statusCode,
                        statusMessage: $statusMessage,
                        additions: $additions
                    ),
                    status: $statusCode,
                    headers: $headers
                );
            }
        } else {
            return response()->json(
                data: self::handleMultiStatus(
                    responses: $data,
                    statusCode: $statusCode,
                    statusMessage: $statusMessage,
                    additions: $additions
                ),
                status: $statusCode,
                headers: $headers
            );
        }
    }

    /**
     * @param array $response
     * @return JsonResponse
     */
    public static function createResponseFromArrayResponse(array $response): JsonResponse
    {
        return self::createResponseFromBase(
            statusCode: $response['status'],
            data: $response['status'] >= 400 && $response['status'] < 500 ?
                $response['errors'] : (array_key_exists('data', $response) ? $response['data'] : null),
            statusMessage: array_key_exists('statusMessage', $response) ? $response['statusMessage'] : null,
            additions: array_diff_key($response, array_flip(['status', 'data', 'errors', 'statusMessage', 'responses']))
        );
    }

    /**
     * @param int $statusCode
     * @param mixed $data
     * @param array $additions
     * @param string|null $statusMessage
     * @param bool $bulk
     * @return array
     */
    public static function createArrayResponse(int    $statusCode,
                                               mixed  $data = null,
                                               array  $additions = [],
                                               string $statusMessage = null,
                                               bool   $bulk = false): array
    {
        $response = [
            'status' => $statusCode
        ];

        is_null($statusMessage) ?: $response += ['statusMessage' => $statusMessage];

        if (!$bulk) {
            if ($statusCode >= 400 && $statusCode < 500) {
                $response += [
                    'errors' => $data
                ];
            } elseif ($statusCode === 204) {
                return $response;
            } else {
                $response += [
                    'data' => $data
                ];
            }
        } else {
            if (is_countable($data) && count($data) < 2) {
                $response = array_merge($response, $data[0]);
            } else {
                $response += ['responses' => $data];
            }
        }

        return array_merge($additions, $response);
    }

    /**
     * @param string $statusMessage
     * @param array|null $data
     * @return array
     */
    public static function ok(string $statusMessage, array $data = null): array
    {
        return self::handle(
            data: $data,
            statusMessage: $statusMessage
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $data
     * @return JsonResponse
     */
    public static function created(string $statusMessage, array $data = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                data: $data,
                statusCode: 201,
                statusMessage: $statusMessage
            ), status: 201
        );
    }

    /**
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(status: 204);
    }

    /**
     * @param string $statusMessage
     * @param array $responses
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function multiStatus(string $statusMessage, array $responses, int $statusCode): JsonResponse
    {
        return response()->json(
            data: self::handleMultiStatus(
                responses: $responses,
                statusCode: $statusCode,
                statusMessage: $statusMessage
            ), status: $statusCode
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function badRequest(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 400,
                statusMessage: $statusMessage
            ), status: 400
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function notFound(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 404,
                statusMessage: $statusMessage
            ), status: 404
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function methodNotAllowed(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 405,
                statusMessage: $statusMessage
            ), status: 405
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function conflict(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 409,
                statusMessage: $statusMessage
            ), status: 409
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function unprocessableEntity(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 422,
                statusMessage: $statusMessage
            ), status: 422
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function locked(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 423,
                statusMessage: $statusMessage
            ), status: 423
        );
    }

    /**
     * @param string $statusMessage
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function failedDependency(string $statusMessage, array $errors = null): JsonResponse
    {
        return response()->json(
            data: self::handle(
                errors: $errors,
                statusCode: 424,
                statusMessage: $statusMessage
            ), status: 424
        );
    }

    /**
     * @param array|null $data
     * @param array|null $errors
     * @param int $statusCode
     * @param string|null $statusMessage
     * @param array|null $additions
     * @return array
     */
    private static function handle(mixed  $data = null,
                                   array  $errors = null,
                                   int    $statusCode = 200,
                                   string $statusMessage = null,
                                   array  $additions = null): array
    {
        $json = [
            'status' => $statusCode
        ];

        is_null($statusMessage) ?: $json += ['statusMessage' => $statusMessage];
        is_null($data) ?: $json += ['data' => $data];
        is_null($errors) ?: $json += ['errors' => $errors];
        is_null($additions) ?: $json = array_merge($json, $additions);

        return $json;
    }

    /**
     * @param array $responses
     * @param int $statusCode
     * @param string|null $statusMessage
     * @param array|null $additions
     * @return array
     */
    private static function handleMultiStatus(array  $responses,
                                              int    $statusCode,
                                              string $statusMessage = null,
                                              array  $additions = null): array
    {
        $json = [
            'status' => $statusCode
        ];

        is_null($statusMessage) ?: $json += ['statusMessage' => $statusMessage];
        is_null($additions) ?: $json = array_merge($json, $additions);

        return $json + ['responses' => $responses];
    }
}
