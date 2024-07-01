<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>List Users</title>
    <?php include '../templates/header.php'; ?>
</head>
<body>

<div class="container">
    <h2 class="text-center m-5">User List</h2>
    <?php include '../includes/db.php'; ?>
    <?php
session_start();

// Check if there is a session message set
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    // Clear the session message after displaying it
    unset($_SESSION['message']);
}
?>
    <?php
    $results_per_page = 5;

    // Find out the number of results stored in the database
    $sql = "SELECT COUNT(id) AS total FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_pages = ceil($row['total'] / $results_per_page);

    // Determine which page number visitor is currently on
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start_from = ($page-1) * $results_per_page;

    // Retrieve selected results from database and display them on page
    $sql = "SELECT u.id, u.name, u.email, u.mobile, u.gender, 
                   COUNT(e.id) AS total_companies, 
                   SUM(e.years * 12 + e.months) AS total_months
            FROM users u 
            LEFT JOIN experiences e ON u.id = e.user_id 
            GROUP BY u.id
            ORDER BY u.id ASC 
            LIMIT $start_from, $results_per_page";
    $result = $conn->query($sql);
    ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Total Companies Served</th>
            <th>Total Experience (Years)</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $total_years = floor($row['total_months'] / 12);
                $remaining_months = $row['total_months'] % 12;
                $total_experience = $total_years + ($remaining_months / 10);

                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['mobile']}</td>
                        <td>{$row['total_companies']}</td>
                        <td>{$total_experience}</td>
                        <td>
                            <a href='update.php?id={$row['id']}' class='btn btn-primary'>Edit</a>
                            <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php
            for ($i=1; $i<=$total_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='read.php?page=".$i."'>".$i."</a></li>";
            }
            ?>
        </ul>
    </nav>
</div>
<?php include '../templates/footer.php'; ?>
<!-- Optional JavaScript; choose one of the two! -->
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
