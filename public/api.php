<?php
include 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Fungsi untuk cek duplikasi nomor telepon
function isDuplicatePhone($conn, $telepon, $excludeId = null) {
    $query = "SELECT id FROM users WHERE telepon = ?";
    if ($excludeId) $query .= " AND id != ?";
    
    $stmt = $conn->prepare($query);
    if ($excludeId) {
        $stmt->bind_param("si", $telepon, $excludeId);
    } else {
        $stmt->bind_param("s", $telepon);
    }
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// Handle Request
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();
            echo json_encode($data);
        } else {
            $result = $conn->query("SELECT * FROM users");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

        case 'POST':
            $nama = $input['nama'];
            $telepon = $input['telepon'];
            $umur = $input['umur'];
        
            // Cek apakah nomor telepon sudah ada
            $checkQuery = $conn->query("SELECT * FROM users WHERE telepon = '$telepon'");
            
            if ($checkQuery->num_rows > 0) {
                echo json_encode(["error" => "Nomor telepon sudah digunakan!"]);
            } else {
                $conn->query("INSERT INTO users (nama, telepon, umur) VALUES ('$nama', '$telepon', $umur)");
                echo json_encode(["message" => "User added successfully"]);
            }
            break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
            exit;
        }

        $id = intval($_GET['id']);
        $nama = $input['nama'];
        $telepon = $input['telepon'];
        $umur = intval($input['umur']);

        if (isDuplicatePhone($conn, $telepon, $id)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nomor telepon sudah digunakan oleh user lain"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET nama = ?, telepon = ?, umur = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nama, $telepon, $umur, $id);
        $stmt->execute();
        
        echo json_encode(["status" => "success", "message" => "User updated successfully"]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
        break;
}

$conn->close();
?>
