<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccurateAPI {

    private $api_url = 'https://account.accurate.id/oauth/authorize'; // Ganti dengan URL API Accurate Online
    private $api_key = '2f3f0d22-52c0-468a-9a5f-d6f2a9a1466b'; // Ganti dengan API Key Anda

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    // Fungsi untuk melakukan GET request ke API Accurate
    public function get($endpoint)
    {
        $ch = curl_init();
        
        // Set options untuk cURL
        curl_setopt($ch, CURLOPT_URL, $this->api_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    // Fungsi untuk melakukan POST request ke API Accurate
    public function post($endpoint, $data)
    {
        $ch = curl_init();

        // Set options untuk cURL
        curl_setopt($ch, CURLOPT_URL, $this->api_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    // Fungsi untuk melakukan PUT request ke API Accurate
    public function put($endpoint, $data)
    {
        $ch = curl_init();

        // Set options untuk cURL
        curl_setopt($ch, CURLOPT_URL, $this->api_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    // Fungsi untuk melakukan DELETE request ke API Accurate
    public function delete($endpoint)
    {
        $ch = curl_init();

        // Set options untuk cURL
        curl_setopt($ch, CURLOPT_URL, $this->api_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
