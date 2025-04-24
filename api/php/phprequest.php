<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REST API Example</title>
    <link rel="stylesheet" type="text/css" href="/api/css/style.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody id="userTableBody"></tbody>
    </table>

    <div class="pagination">
        <button id="prevPage" onclick="changePage('prev')">Previous</button>
        <span id="pageInfo">Page 1</span>
        <button id="nextPage" onclick="changePage('next')">Next</button>
    </div>

    <script src="/api/scripts/paginator.js"></script>
</body>
</html>
