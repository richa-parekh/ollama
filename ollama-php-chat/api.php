<?php
// Header for Streaming respons
header('Content-Type: text/plain'); // For streaming text
header('Cache-Control: no-cache'); // For not cache data
header('Connection: keep-alive'); // For keepconnection open

// POST request valodation: Only POST request allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only Post Method Allowed!']);
    exit;
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$prompt = isset($input['prompt']) ? trim($input['prompt']) : '';

if (empty($prompt)) {
    http_response_code(400);
    echo json_encode(['error' => 'Input is required']);
    exit;
}

// Configration
define('OLLAMA_BASE_URL', 'http://localhost:11434/api');
define('MODEL_NAME', 'llama3.2:1b');
define('CURL_TIMEOUT', 300); // 5 min

// Main execution
try {
    if(checkModelAvailability()){
        generateResponse($prompt);
    }else {
        http_response_code(503);
        echo json_encode(['error' => "Model not available"]);
    }
} catch (Exception $e){
    http_response_code(500);
    echo json_encode(['error' => "Internal Server error: ", $e->getMessage()]);
}

/* Check if model is available */
function checkModelAvailability()
{
    $url = OLLAMA_BASE_URL . "/show";
    $postData = ['model' => MODEL_NAME];
    $response = callOllamaAPI($url, $postData, false);

    return $response !== false;
}

/* Generate response using ollama API */
function generateResponse($prompt)
{
    $url = OLLAMA_BASE_URL . "/generate";
    $postData = array(
        'model' => MODEL_NAME,
        "prompt" => $prompt,
        'stream' => true
    );
    $response = callOllamaAPI($url, $postData, true);

    if (!$response) {
       http_response_code(500);
       echo json_encode(['error' => 'Failed to generate response']);
    }
}

/* Handle streaming data */
function handleStreamData($curl, $data){
    $lines = explode("\n", trim($data));
    foreach($lines as $line){
        $line = trim($line);
        if(empty($line)) continue;

        $response = json_decode($line, true);
        if($response && isset($response['response'])){
            echo $response['response'];
            flush();

            if(isset($response['done']) && $response['done']){
                break;
            }
        }
    }
    return strlen($data);
}

/* Call ollama API */
function callOllamaAPI($url, $postData, $isStream)
{
    $curl = curl_init();
    
    $curlOptions = [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => !$isStream
    ];
   
    if($isStream){
        $curlOptions[CURLOPT_WRITEFUNCTION] = 'handleStreamData';
    }

    curl_setopt_array($curl, $curlOptions);
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if($curlError){
        error_log("CURL Error: ". $curlError);
        return false;
    }
    if ($http_code !== 200) {
        error_log("HTTP Error: ". $curlError);
        return false;
    }

    return $isStream ? true : $response;
}
?>