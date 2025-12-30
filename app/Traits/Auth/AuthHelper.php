<?php

namespace App\Traits\Auth;

use App\Traits\Api\ApiHelper;

trait AuthHelper
{
    use ApiHelper;

    /**
     * loginApi
     *
     * @param  mixed $data
     * @return void
     */
    public function loginApi($data)
    {
        $response = $this->authenticationRequest('/login', $data);
        if ($response['status'] == 'success') {
            return ['status' => 'success', 'data' => $response['data']];
        } else {
            $message = $response['message'];
            return ['status' => 'error', 'message' => $message];
        }
    }
    /**
     * registerApi
     *
     * @param  mixed $data
     * @return void
     */
    public function registerApi($data)
    {
        $response = $this->authenticationRequest('/register', $data);
        if ($response['status'] == 'success') {
            return ['status' => 'success', 'data' => $response['data']];
        } else {
            $message = $response['message'];
            return ['status' => 'error', 'message' => $message];
        }
    }
    /**
     * logoutApi
     *
     * @return void
     */
    public function logoutApi()
    {
        $response = $this->deleteMethodRequest('/logout');

        if ($response['status'] == 'success') {
            return ['status' => 'success', 'data' => $response['data']];
        } else {
            $message = $response['message'];
            return ['status' => 'error', 'message' => $message];
        }
    }
}
