<?php

namespace Esyede\WebSMS;

class WebSMS
{
    private $token;
    private $domain;

    /**
     * Constructor.
     *
     * @param string $token  Token / api key akun anda
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->domain = 'https://websms.co.id';
    }

    /**
     * Send a regular SMS.
     *
     * @param string $receiverNumber  Receiver number (Format: 081234567890)
     * @param string $message         Message content
     *
     * @return string
     */
    public function regular($receiverNumber, $message)
    {
        $payloads = [
            'token' => $this->token(),
            'to' => $receiverNumber,
            'msg' => urlencode($message),
        ];

        return $this->request('/api/smsgateway', $payloads);
    }

    /**
     * Send a VIP (prioritized) SMS.
     *
     * @param string $receiverNumber  Receiver number (Format: 081234567890)
     * @param string $message         Message content
     *
     * @return string
     */
    public function vip($receiverNumber, $message)
    {
        $payloads = [
            'token' => $this->token(),
            'to' => $receiverNumber,
            'msg' => urlencode($message),
        ];

        return $this->request('/api/smsgateway-vip', $payloads);
    }

    /**
     * Send a OTP SMS.
     *
     * @param string $receiverNumber  Receiver number (Format: 081234567890)
     * @param string $message         Message content
     *
     * @return string
     */
    public function otp($receiverNumber, $message)
    {
        $payloads = [
            'token' => $this->token(),
            'to' => $receiverNumber,
            'msg' => urlencode($message),
        ];

        return $this->request('/api/smsgateway-otp', $payloads);
    }

    /**
     * Send a request
     *
     * @param string $endpoint
     * @param array  $payloads
     *
     * @return string
     */
    public function request($endpoint, array $payloads)
    {
        $payloads['message'] = urlencode($payloads['message']);
        $endpoint . '?' . http_build_query($payloads);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $results = curl_exec($ch);
        $errors = curl_error($ch);

        curl_close($ch);

        if ($errors) {
            return json_encode([
                'status' => 'error',
                'message' => $errors,
                'payloads' => $payloads,
            ]);
        }

        $decoded = json_decode($results);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return json_encode([
                'status' => 'error',
                'message' => 'Unable to decode json response',
                'payloads' => $payloads,
            ]);
        }

        $decoded['payloads'] = $payloads;

        return json_encode($decoded);
    }
}