<?php

namespace App\Traits\Encrypt;

use Illuminate\Support\Str;

trait EncryptHelper
{
    /**
     * Encrypt a string.
     *
     * @param string $data
     * @return string
     */
    public function custom_encrypt($data)
    {
        $cipher = 'AES-256-CBC';
        $key = substr(hash('sha256', config('encrypt.encryption_key')), 0, 32);
        $iv = Str::random(16);

        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt an encrypted string.
     *
     * @param string $encryptedData
     * @return string
     */
    public function custom_decrypt($encryptedData)
    {
        $cipher = 'AES-256-CBC';
        $key = substr(hash('sha256', config('encrypt.encryption_key')), 0, 32);
        $data = base64_decode($encryptedData);

        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
}
