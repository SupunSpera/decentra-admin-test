<?php

namespace App\Traits\Api;

use Illuminate\Support\Facades\Http;

trait ApiHelper
{
    /**
     * successResponse
     *
     * @param  mixed $response
     * @param  mixed $responseCode
     * @return void
     */
    public function successResponse($response, $responseCode)
    {
        return response()->json([
            'status' => 'success',
            'data' => $response,
        ], $responseCode)
            ->header('Content-Type', 'application/json');
    }
    /**
     * errorResponse
     *
     * @param  mixed $message
     * @param  mixed $responseCode
     * @return void
     */
    public function errorResponse($message, $responseCode)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $responseCode)
            ->header('Content-Type', 'application/json');
    }
    /**
     * noDataResponse
     *
     * @param  mixed $responseCode
     * @return void
     */
    public function noDataResponse($responseCode)
    {
        return response()->json([
            'status' => 'no-data',
            'message' => 'No data found for the specified criteria.',
            'data' => [],
        ], $responseCode)
            ->header('Content-Type', 'application/json');
    }

    /**
     * getMethodRequest
     *
     * @param  mixed $url
     * @return void
     */
    public function getMethodRequest($endPoint)
    {
        $token = session('passportToken');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(config('path.api_base_url') . $endPoint);
        if ($response->successful()) {
            return $response->json();
        } else {
            $errorMessage = 'API request failed with status code: ' . $response->status();
            $message = $response->json()['message'];
            return ['status' => 'error', 'error' => $errorMessage, 'message' => $message];
        }
    }
    /**
     * postMethodRequest
     *
     * @param  mixed $endPoint
     * @param  mixed $data
     * @return void
     */
    public function postMethodRequest($endPoint, $data)
    {
        $token = session('passportToken');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(config('path.api_base_url') . $endPoint, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            $errorMessage = 'API request failed with status code: ' . $response->status();
            $message = $response->json()['message'];
            return ['status' => 'error', 'error' => $errorMessage, 'message' => $message];
        }
    }
    /**
     * deleteMethodRequest
     *
     * @param  mixed $endPoint
     * @return void
     */
    public function deleteMethodRequest($endPoint)
    {
        $token = session('passportToken');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete(config('path.api_base_url') . $endPoint);
        if ($response->successful()) {
            return $response->json();
        } else {
            $errorMessage = 'API request failed with status code: ' . $response->status();
            return ['status' => 'error', 'error' => $errorMessage];
        }
    }
    /**
     * authenticationRequest
     *
     * @param  mixed $endPoint
     * @param  mixed $data
     * @return void
     */
    public function authenticationRequest($endPoint, $data)
    {
        $token = config('path.api_token');

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post(config('path.api_base_url') . $endPoint, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            $errorMessage = 'API request failed with status code: ' . $response->status();
            $message = $response->json()['message'];
            return ['status' => 'error', 'error' => $errorMessage, 'message' => $message];
        }
    }
}
