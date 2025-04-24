<?php
include 'db/db.php';
$db = new Database(); // instantiate the class
$conn = $db->getConnection(); // get the mysqli connection
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Utility function for API response
function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

switch ($method) {
    case 'GET':
        // Default values for pagination
        $page  = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;

        // Set limit and offset based on query parameters
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0
            ? (int) $_GET['limit']
            : 10;

        $offset = ($page - 1) * $limit;

        // If 'limit' is set to 'all', fetch all records (no pagination)
        if (isset($_GET['limit']) && $_GET['limit'] === 'all') {
            $limit = null;  // Set to null or flag to indicate all records
            $offset = 0;    // Reset offset for all records
        }

        // If an ID is provided, fetch a single user by ID
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            respond($data ?: ["error" => "User not found"], $data ? 200 : 404);
        } else {
            // Get total count for pagination
            $countResult = $conn->query("SELECT COUNT(*) AS total FROM users");
            $total = $countResult->fetch_assoc()['total'];

            // If limit is null (all records), fetch all
            if ($limit === null) {
                $query = "SELECT * FROM users";  // No limit, return all
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }

                respond(["data" => $users]);
            } else {
                // Paginated query
                $stmt = $conn->prepare("SELECT * FROM users LIMIT ?, ?");
                $stmt->bind_param("ii", $offset, $limit);
                $stmt->execute();
                $result = $stmt->get_result();

                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }

                // Respond with paginated data and pagination metadata
                respond([
                    "page"  => $page,
                    "limit" => $limit,
                    "total" => (int) $total,
                    "pages" => ceil($total / $limit),
                    "data"  => $users
                ]);
            }
        }

        break;

    case 'POST':
        $data = $input;

        if (!$data) {
            respond(["error" => "No input provided"], 400);
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email, age) VALUES (?, ?, ?)");

        if (isset($data[0])) {
            // Array of records (bulk insert)
            foreach ($data as $record) {
                $name = $record['name'] ?? '';
                $email = $record['email'] ?? '';
                $age = (int) ($record['age'] ?? 0);
                $stmt->bind_param("ssi", $name, $email, $age);

                // Check for query execution
                if (!$stmt->execute()) {
                    respond(["error" => "Failed to insert record"], 500);
                }
            }
        } else {
            // Single record
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $age = (int) ($data['age'] ?? 0);
            $stmt->bind_param("ssi", $name, $email, $age);

            // Check for query execution
            if (!$stmt->execute()) {
                respond(["error" => "Failed to insert record"], 500);
            }
        }

        respond(["message" => "User(s) added successfully"]);
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            respond(["error" => "Missing user ID"], 400);
        }

        $id = (int) $_GET['id'];
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $age = (int) ($input['age'] ?? 0);

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, age = ? WHERE id = ?");
        $stmt->bind_param("ssii", $name, $email, $age, $id);

        // Check for query execution
        if (!$stmt->execute()) {
            respond(["error" => "Failed to update user"], 500);
        }

        respond(["message" => "User updated successfully"]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            respond(["error" => "Missing user ID"], 400);
        }

        $id = (int) $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Check for query execution
        if (!$stmt->execute()) {
            respond(["error" => "Failed to delete user"], 500);
        }

        respond(["message" => "User deleted successfully"]);
        break;

    default:
        respond(["error" => "Invalid request method"], 405);
}

$conn->close();
?>
