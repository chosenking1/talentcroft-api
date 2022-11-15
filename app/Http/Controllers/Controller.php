<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Return JSON Response
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function jsonResponse(array $data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Some operation (save only?) has completed successfully
     * @param mixed $data
     * @param int $code
     * @return mixed
     */
    final public function respondWithSuccess(mixed $data, int $code = 200): JsonResponse
    {
        return $this->jsonResponse(is_array($data) ? $data : ['data' => $data, 'message' => "Success", 'status' => true], $code);
    }

    /**
     * Respond with an Error
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    final public function respondWithError(mixed $data = 'There was an error', int $code = 400): JsonResponse
    {
        return $this->jsonResponse(is_array($data) ? $data : ['message' => $data, 'status' => false, "data" => null], $code);
    }

    /**
     * @param mixed|string $data
     * @param int $code
     * @return JsonResponse
     */
    final public function respondWithErrors(mixed $data = 'There was an error', int $code = 400): JsonResponse
    {
        return $this->jsonResponse(['errors' => $data], $code);
    }

    /**
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @throws ValidationException
     */
    final public function validateData(array $rules, array $messages = [], array $customAttributes = [])
    {
        $request = request();
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) throw new ValidationException($validator);
    }
}
