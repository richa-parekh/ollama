<?php
$url = "http://localhost:11434/api/generate";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
    "X-Custom-Header: header-value",
    "Content-Type: application/json"
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HEADER, true);

$postData = array (
    'model' => 'llama3.2:1b',
    'prompt' => "Why is the sky blue?",
);

$postFields = json_encode($postData);

curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($curl, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
print_r($response);
if ($response === false) {
    $error = curl_error($curl);
    echo $error;
    // Handle the error
} else {
    // Process the response
    if ($response == "OK") {
        // Request was successful
        echo $response;
    } else {
        // Handle other responses
        echo "else 2";
    }
}

curl_close($curl);

