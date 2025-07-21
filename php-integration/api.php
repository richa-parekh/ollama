<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only Post Method Allowed!']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'];

if (!empty($prompt) && strlen(trim($prompt)) > 0) {
    $result = checkModel($prompt);
    if (!$result) {
        echo json_encode(['error' => 'Model not available!']);
    }
} else {
    echo json_encode(['error' => 'Input is required']);
}

function callOllamaAPI($url, $postData)
{
    $headers = array(
        "Content-Type: application/json"
    );

    $postFields = json_encode($postData);
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 120);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return false;
    }

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code == 200) {
        return $response;
    } else {
        return false;
    }
}

function checkModel($prompt)
{
    $url = "http://localhost:11434/api/show";
    $postData = array('model' => 'llama3.2:1b');
    $response = callOllamaAPI($url, $postData);

    if ($response) {
        return loadModel($prompt);
    }
    return false;
}

function loadModel($prompt)
{
    $url = "http://localhost:11434/api/generate";
    $postData = array(
        'model' => 'llama3.2:1b',
        "prompt" => $prompt,
        'stream' => false
    );
    $response = callOllamaAPI($url, $postData);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['response'])) {
            echo json_encode(['response' => $data['response']]);
            return true;
        }
    }
    echo json_encode(['error' => 'Failed to generate response']);
    return false;
}
?>