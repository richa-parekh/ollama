<?php
/* print_r($_POST['input']);die; */
$prompt = $_POST['input'];
if(!empty($prompt)){
    checkModel($prompt);
}else{
    echo "Input is required";
}



function checkModel($prompt)
{
    $url = "http://localhost:11434/api/show";
    $headers = array(
        "X-Custom-Header: header-value",
        "Content-Type: application/json"
    );
    $postData = array(
        'model' => 'llama3.2:1b',
    );

    $postFields = json_encode($postData);
    $curl = curl_init();
    try {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        /* echo "<pre>";
        print_r($response);
        echo "</pre>"; */

        if (curl_errno($curl)) {
            echo curl_error($curl);
            die();
        }

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        /* echo "<pre>";
        print_r($http_code);
        echo "</pre>"; */

        if ($http_code == intval(200)) {
            loadModel($prompt);
        } else {
            echo "Resource introuvable : " . $http_code;
        }
    } catch (\Throwable $th) {
        throw $th;
    } finally {
        curl_close($curl);
    }
}

function loadModel($prompt)
{  /*  echo "loadmodel"; */
    $url = "http://localhost:11434/api/generate";
    $headers = array(
        "X-Custom-Header: header-value",
        "Content-Type: application/json"
    );
    $postData = array(
        'model' => 'llama3.2:1b',
        "prompt" => $prompt,
        'stream' => false  // Important: set to false for single response
    );

    $postFields = json_encode($postData);
    $curl = curl_init();
    try {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // For localhost
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false); // Don't include headers in response
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // Timeout after 120 seconds
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        /* echo "<pre>";
        print_r($response);
        echo "</pre>"; */
        $data = json_decode($response, true);
        /* echo "<pre>";
        print_r($data['response']);
        echo "</pre>"; */

        if (curl_errno($curl)) {
            echo curl_error($curl);
            die();
        }

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        /* echo "<pre>";
        print_r($http_code);
        echo "</pre>";
 */
        if ($http_code == intval(200)) {
            if (isset($data['response'])) {
                echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
                echo "<h3>AI Response:</h3>";
                echo nl2br(htmlspecialchars($data['response']));
                /* echo "<p><small>Model: " . $result['model'] . " | Duration: " . number_format($result['total_duration'] / 1000000000, 2) . "s</small></p>"; */
                echo "</div>";
            }
        } else {
            echo "Resource introuvable : " . $http_code;
        }
    } catch (\Throwable $th) {
        throw $th;
    } finally {
        curl_close($curl);
    }
}
