<?php
 
    
    // Display the user's information
    //echo "Welcome, $username! Your user ID is $userID.";
    // Database configuration
    require_once "connect.php";
   
    
    // Initialize variables
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $row = [];

    // Fetch reservation data
    if ($id) {
        $sql = "SELECT * FROM reserve_book rb 
        left join book_info b on rb.book_id = b.id where reserve_id = '$id'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die("id not found");
        }
    }

    // Update reservation data
    if (isset($_POST['registers'])) {
        date_default_timezone_set("Asia/Bangkok");
        $d = $_POST['id'];
        $status = $_POST['status'];
        $datetime = date('Y-m-d H:i:s');
         
        // Insert into log table
        $bookst = "update book_info set book_status = '$status' where id = '$d' ";
        $resultst = $conn->query($bookst);

        // Insert into log table
        $sqlLog = "INSERT INTO log_bookstatus (book_status,update_datetime) 
        VALUES ('$status', '$datetime')";
        $resultsqlLog = $conn->query($sqlLog);

        

    


        if (!$resultst) {
            die("Update failed: " . $conn->error);
        }
        else {
            echo '<script>';
            echo 'alert("บันทึกสำเร็จ!");';
            echo 'window.opener.location.reload();';  // Refresh parent page
            echo 'window.close();';  // Close popup window
            echo '</script>';
        }

    }

    


?>



<!DOCTYPE html>
<html>
<head>
    <title>จองหนังสือ</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
            <h2>จองหนังสือ <?php echo  $row['title']; ?></h2>
        </div>
        <div class="card-body">
        <form method="POST" action="managebook.php">
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $row['id']; ?>" style="display: none;" readonly>


            <div class="form-group">
                <label for="title"><b>ชื่อหนังสือ: </b> <?php echo $row['title']; ?></label>
            </div>

            <div class="form-group">
                <label for="reservename"><b>ชื่อผู้ยืม: </b> <?php echo $row['reserve_name']; ?></label>
            </div>

            <div class="form-group">
                <label for="reservename"><b>เบอร์ติดต่อภายใน: </b> <?php echo $row['reserve_phone']; ?></label>

            </div>

            <div class="form-group">
                <label for="start"><b>ยืมวันที่: </b> <?php echo $row['reserve_date']; ?></label>

            </div>

            <div class="form-group">
                <label for="title"><b>สถานะ:</b></label>
                <?php $st = $row['book_status'];?>
                
                <select class="form-control" name="status" id="status" required>
                    <?php
                        $query = "SELECT id, name FROM book_status";
                        $status = mysqli_query($conn, $query);
                        while ($row2 = mysqli_fetch_assoc($status)) {
                            $id = $row2['id'];
                            $sta = $row2['name'];
                            echo "<option value='$id'";
                            echo ($st == $id) ? "selected" : ""; // Use ternary operator here
                            echo ">$sta</option>";
                        }
                    ?>
                </select>
            </div>

            <button type="submit" name="registers" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
</div>
</div>
</body>
</html>
