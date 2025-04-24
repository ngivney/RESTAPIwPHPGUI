<?php
  
  require_once 'ApiClient.php';
  $client = new ApiClient();
  $response = $client->sendRequest('PUT', $_POST, $_POST['id']);
  echo $response;
  header("Location: ../index.php");
  exit;

?>
