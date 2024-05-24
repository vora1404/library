<?php
    $sessionTimeout = 3600;

    // Set the session.gc_maxlifetime configuration option
    ini_set('session.gc_maxlifetime', $sessionTimeout);
    
    // Start the session
    session_start();
    
    // Check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        // User is not logged in, redirect to the login page
        header("Location: login.php");
        exit();
    }
    
    // Access the user's information from the session
    $userID = $_SESSION["user_id"];
    $username = $_SESSION["username"];
    
    // Display the user's information
    //echo "Welcome, $username! Your user ID is $userID.";
    // Database configuration
    require_once "connect.php";
   
    
    // Initialize variables
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $row = [];

    // Fetch reservation data
    if ($id) {
        $sql = "SELECT b.id,title,author,description,cover,c.category,book_status from book_info b
        left join category c on b.category_id = c.id
        WHERE b.id = $id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die("id not found");
        }
    }

    // Update reservation data
// Update reservation data
if (isset($_POST['registers'])) {
    // Prepare and bind parameters for the update
    $stmt = $conn->prepare("UPDATE book_info SET title = ?, author = ?, category_id = ?, description = ?, price = ?, cover = ? WHERE id = ?");
    $stmt->bind_param("ssisdsi", $title, $author, $category_id, $description, $price, $cover_data, $id);

    // Set parameters for the update
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];
    $price = '';

    // Check if a new cover file is uploaded
    if ($_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        // Read the cover image data
        $cover = $_FILES['cover']['tmp_name'];
        $cover_data = file_get_contents($cover);
    } elseif (isset($_POST['remove_cover'])) {
        // If cover removal is requested, set cover data to NULL
        $cover_data = null;
    }

    // Execute the update statement
    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully'); window.opener.location.reload(); window.close();</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}


    
?>



<!DOCTYPE html>
<html>
<head>
    <title>book</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="/com/styles.css">    

</head>
<body>
    <div class="container">
    <div class="card">
        <div class="card-header">
            <h2>รายการหนังสือ</h2>
        </div>
        <div class="card-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="text" class="form-control" name="id" id="hud" value="<?php echo $row['id']; ?>" readonly style="display: none;">
            
            <div class="form-group">
                <label for="password"><b>ชื่อหนังสือ:</b></label>
                <input type="text" class="form-control"  name="title" id="hsn" value="<?php echo $row['title']; ?>" >
            </div>




            <div class="form-group">
                <label for="dep"><b>หมวด:</b></label>
                <?php $ht = $row['category'];  ?>
                <select class="form-control" name="category" id="dep" required>
                    <?php
                        $query = "SELECT id, category FROM category";
                        $dep = mysqli_query($conn, $query);
                        while ($row3 = mysqli_fetch_assoc($dep)) {
                            $id = $row3['id'];
                            $depName = $row3['category'];
                            echo "<option value='$id'";
                            echo ($ht == $depName) ? "selected" : ""; // Use ternary operator here
                            echo ">$depName</option>";
                        }
                    ?>
                </select>
                <script>
                    $(document).ready(function() {
                    // Initialize Select2 on the dropdown select element
                    $('#dep').select2();
                    });
                </script>
            </div>

            <div class="form-group">
                <label for="ip"><b>ผู้เชียน:</b></label>
                <input type="text" class="form-control"  name="author" id="ip" value="<?php echo $row['author']; ?>">
            </div>

            <div class="form-group">
                <label for="details"><b>รายละเอียด:</b></label>
                <input type="text" class="form-control"  name="description" id="details" value="<?php echo $row['description']; ?>">
            </div>

            <div class="form-group">
                <label for="details"><b>รูปหน้าปก:</b></label>
                <input type="file" class="form-control" id="cover" name="cover" accept="image/*" >
                <label><input type="checkbox" name="remove_cover"> Remove Cover Image</label>
            </div>


            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="status" <?php echo ($row['book_status'] == '1') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="customSwitch1">เปิด/ปิดการใช้งาน</label>
            </div>



    

            <button type="submit" name="registers" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
</div>
</div>
</body>
</html>
