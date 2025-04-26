<?php
require_once 'ApiClient.php';

// Default message
$message = '';
$messageClass = '';

// Display a message if a status is passed via query string
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = 'User updated successfully!';
        $messageClass = 'alert success';
    } elseif ($_GET['status'] === 'error') {
        $message = 'There was an error updating the user.';
        $messageClass = 'alert error';
    }
}

$client = new ApiClient();
$response = $client->sendRequest('GET', [], $_GET['id']);

if ($response === false) {
    echo 'Error retrieving user data';
} else {
    $data = json_decode($response, true);
    $id    = $data['id'];
    $name  = $data['name'];
    $email = $data['email'];
    $age   = $data['age'];
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Update User</title>
</head>
<body>
    <h1>REST API with PHP GUI</h1>

    <?php if (!empty($message)): ?>
        <div class="<?php echo $messageClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <td>
                <form action="http://localhost/api/">
                    <input type="submit" value="BACK" />
                </form>
                <form action='phpsubmit.php' method='post' accept-charset='UTF-8' enctype='multipart/form-data'> 
                    <h2>Update User</h2>

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <strong>Name<br></strong> 
                    <input id='name' name='name' type='text' value="<?php echo htmlspecialchars($name); ?>" required>
                    <br>

                    <strong>Email<br></strong> 
                    <input id='email' name='email' type='email' value="<?php echo htmlspecialchars($email); ?>" required>
                    <br>

                    <strong>Age<br></strong> 
                    <input id='age' name='age' type='number' min="0" value="<?php echo htmlspecialchars($age); ?>" required>
                    <br>
                    <input type="hidden" id="method" name="method" value="PUT">
                    <input type='submit' value='Submit'>
                </form>
            </td>
        </tr>
    </table>
</body>
</html>
