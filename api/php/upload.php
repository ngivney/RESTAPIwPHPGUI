<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['jsonFile']) && $_FILES['jsonFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['jsonFile']['tmp_name'];
        $fileName = $_FILES['jsonFile']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (strtolower($fileExtension) !== 'json') {
            die("Invalid file type. Please upload a .json file.");
        }

        $jsonContent = file_get_contents($fileTmpPath);
        $jsonData = json_decode($jsonContent, true);

        if (!is_array($jsonData)) {
            die("Invalid JSON format or structure. Expected an array of records.");
        }

        // Send JSON to API
        require_once 'ApiClient.php';
        $client = new ApiClient();
        $response = $client->sendRequest('POST', $jsonData );

        if ($response === false) {
            echo "❌ Error: Could not send data to the API.";
        } else {
            echo "<h3>✅ Records sent to API successfully!</h3>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
    } else {
        echo "File upload error. Code: " . $_FILES['jsonFile']['error'];
    }
} else {
    echo "Invalid request method.";
}

//$responseData = json_decode($response, true);
//$message = isset($responseData['message']) ? $responseData['message'] : 'Upload complete';

// Redirect with message
//header("Location: index.php?message=" . urlencode($message));
header("Location: ../index.php");
exit;
?>

