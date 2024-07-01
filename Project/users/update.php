<?php
include '../includes/db.php';
session_start();
// Check if ID parameter is passed
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Fetch experiences
        $sql = "SELECT * FROM experiences WHERE user_id = $user_id";
        $result = $conn->query($sql);
        $experiences = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $experiences[] = $row;
            }
        }
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required POST variables are set
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['gender']) &&
        isset($_POST['company']) && isset($_POST['years']) && isset($_POST['months'])) {
        
        // Update user data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $gender = $_POST['gender'];

        $sql = "UPDATE users SET name='$name', email='$email', mobile='$mobile', gender='$gender' WHERE id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            // Delete existing experiences for the user
            $deleteSql = "DELETE FROM experiences WHERE user_id='$user_id'";
            $conn->query($deleteSql);

            // Insert updated experiences data
            $companies = $_POST['company'];
            $years = $_POST['years'];
            $months = $_POST['months'];

            for ($i = 0; $i < count($companies); $i++) {
                $company = $companies[$i];
                $year = $years[$i];
                $month = $months[$i];

                $insertSql = "INSERT INTO experiences (user_id, company, years, months) VALUES ('$user_id', '$company', '$year', '$month')";
                $conn->query($insertSql);
            }

            // Set session message for success
            $_SESSION['message'] = "User and experiences updated successfully.";
            
            // Redirect to the user list page
            header("Location: read.php");
            exit;
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            '  . $conn->error.'
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';
           
        }
    } else {
        echo "All form fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addExperience() {
            var experienceDiv = document.createElement('div');
            experienceDiv.className = 'form-row';
            experienceDiv.innerHTML = `
                <div class="form-group col-md-4">
                    <label>Company</label>
                    <input type="text" class="form-control" name="company[]" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Years</label>
                    <input type="number" class="form-control" name="years[]" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Months</label>
                    <input type="number" class="form-control" name="months[]" required>
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-danger mt-4" onclick="removeExperience(this)">Remove</button>
                </div>
            `;
            document.getElementById('experiences').appendChild(experienceDiv);
        }

        function removeExperience(button) {
            button.parentElement.parentElement.remove();
        }

        // Function to pre-fill form with existing data
        function preFillForm() {
            document.getElementById('name').value = "<?php echo $user['name']; ?>";
            document.getElementById('email').value = "<?php echo $user['email']; ?>";
            document.getElementById('mobile').value = "<?php echo $user['mobile']; ?>";
            document.getElementById('gender').value = "<?php echo $user['gender']; ?>";

            // Pre-fill experiences if available
            <?php foreach ($experiences as $exp): ?>
                var experienceDiv = document.createElement('div');
                experienceDiv.className = 'form-row';
                experienceDiv.innerHTML = `
                    <div class="form-group col-md-4">
                        <label>Company</label>
                        <input type="text" class="form-control" name="company[]" value="<?php echo $exp['company']; ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Years</label>
                        <input type="number" class="form-control" name="years[]" value="<?php echo $exp['years']; ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Months</label>
                        <input type="number" class="form-control" name="months[]" value="<?php echo $exp['months']; ?>" required>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-danger mt-4" onclick="removeExperience(this)">Remove</button>
                    </div>
                `;
                document.getElementById('experiences').appendChild(experienceDiv);
            <?php endforeach; ?>
        }

        // Call preFillForm() when the page loads
        window.onload = preFillForm;
    </script>
    <?php include '../templates/header.php'; ?>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Update User</h2>
    <form action="update.php?id=<?php echo $user_id; ?>" method="post">
        <div class="form-group">
            <label>Name</label>
            <input id="name" type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input id="email" type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input id="mobile" type="text" class="form-control" name="mobile" required>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select id="gender" class="form-control" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <h3>Experiences</h3>
        <div id="experiences">
            <!-- Experiences will be dynamically filled here -->
        </div>

        <button type="button" class="btn btn-primary mt-2" onclick="addExperience()">Add Experience</button><br><br>
        <input type="submit" class="btn btn-success" value="Update">
    </form>
</div>

</body>
</html>
