<?php

class ApiClient {
    private string $baseUrl = 'http://localhost/api/api.php';

    public function sendRequest(string $method, array $data = [], ?int $id = null): string {
        $url = $this->baseUrl;

        if ($id !== null) {
            $url .= "?id=" . urlencode($id);
        }

        $options = [
            'http' => [
                'method'  => strtoupper($method),
                'header'  => 'Content-Type: application/json',
                'content' => json_encode($data),
            ]
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return json_encode(["error" => "Request failed"]);
        }

        return $response;
    }
}

