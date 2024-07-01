<?php
include '../includes/db.php';
session_start();
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete user's experiences
        $stmt = $conn->prepare("DELETE FROM experiences WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

         // Set session message for success
         $_SESSION['message'] = "User and experiences deleted successfully.";
    } catch (Exception $e) {
        // Rollback the transaction if there is an error
        $conn->rollback();
        echo "Error deleting user and experiences: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

$conn->close();

// Redirect to the user list page
header("Location: read.php");
exit;
?>
