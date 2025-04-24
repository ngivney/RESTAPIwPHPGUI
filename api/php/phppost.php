<?php
  
  require_once 'ApiClient.php';
  $client = new ApiClient();
  $response = $client->sendRequest('POST', $_POST );
  echo $response;
  header("Location: ../index.php");
  exit;

?>