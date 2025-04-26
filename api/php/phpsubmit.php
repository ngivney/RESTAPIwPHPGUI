<?php

  require_once 'ApiClient.php';
  $client = new ApiClient();

    if (isset($_GET['method'])){
        $method = $_GET['method'];
    }
    else {
        $method = $_POST['method'];
    }

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

        $dataholder= $jsonData;
    }
    else{
        if($method === 'DELETE'){
            $dataholder= array();
        }
        else{
            $dataholder= $_POST;
        }
    }

    if (isset($_GET['id'])) {
        $response = $client->sendRequest($method, $dataholder, $_GET['id']);
    }
    if (isset($_POST['id']) && $method === 'PUT'){
        $response = $client->sendRequest($method, $dataholder, $_POST['id']);        
    }
    else{
        $response = $client->sendRequest($method, $dataholder );
    }
    
    header("Location: ../index.php");
    exit;

?>
