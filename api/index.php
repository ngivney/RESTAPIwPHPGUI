<html>
<link rel="stylesheet" type="text/css" href="css/style.css">
<body>
    <h1>REST API with PHP GUI</h1>
    <?php include_once 'php/phprequest.php';?>
    <br>
    <table>
        <tr>
            <td>
                <form action='php/phppost.php' method='post' accept-charset='UTF-8' enctype='multipart/form-data'> 
                    <h2>Post</h2>
                    <strong>Name<br></strong> 
                        <input id='name' name='name' type='text'>
                        <br>
                        <strong>Email<br></strong> 
                        <input id='email' name='email' type='text'>
                        <br>
                        <strong>Age<br></strong> 
                        <input id='age' name='age' type='text'>
                        <br>
                        <input type='submit' value='Submit'>
                </form>
            </td>
            <td>
                <form action="php/upload.php" method="POST" enctype="multipart/form-data">
                    <h2>Upload JSON File</h2>
                    <input type="file" name="jsonFile" accept=".json" required>
                    <input type="submit" value="Upload JSON">
                </form>
            </td>
        </tr>
    </table>
    <!-- script src="script.js"></script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("delete-link")) {
            const confirmed = confirm("Are you sure you want to delete this record?");
            if (!confirmed) {
                e.preventDefault();
            }
        }
        });
        });
</script>
</body>
</html>
