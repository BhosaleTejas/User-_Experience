<?php
include '../includes/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required POST variables are set
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['gender']) &&
        isset($_POST['company']) && isset($_POST['years']) && isset($_POST['months'])) {
        
        // Insert user data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $gender = $_POST['gender'];

        $sql = "INSERT INTO users (name, email, mobile, gender) VALUES ('$name', '$email', '$mobile', '$gender')";
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;

            // Insert experiences data
            $companies = $_POST['company'];
            $years = $_POST['years'];
            $months = $_POST['months'];

            for ($i = 0; $i < count($companies); $i++) {
                $company = $companies[$i];
                $year = $years[$i];
                $month = $months[$i];

                $sql = "INSERT INTO experiences (user_id, company, years, months) VALUES ('$user_id', '$company', '$year', '$month')";
                $conn->query($sql);
            }
            
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    User and experiences added successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
       ' . $conn->error.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
           
        }
    } else {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        All form fields are required.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
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
    </script>
     <?php include '../templates/header.php'; ?>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Create User</h2>
    <form action="create.php" method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input type="text" class="form-control" name="mobile" required>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select class="form-control" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <h3>Experiences</h3>
        <div id="experiences">
            <div class="form-row">
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
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="col">
                <button type="button" class="btn btn-primary" onclick="addExperience()">Add Experience</button>
            </div>
            <div class="col">
                <input type="submit" class="btn btn-success" value="Submit">
            </div>
        </div>
    </form>
   
</div>

</body>
</html>
