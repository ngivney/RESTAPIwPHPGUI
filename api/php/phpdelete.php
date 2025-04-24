<?php

  require_once 'ApiClient.php';
  $client = new ApiClient();
  $response = $client->sendRequest('DELETE', [], $_GET['id']);
  echo $response;
  header("Location: ../index.php");
  exit;

?>
