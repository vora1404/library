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
        $sql = "SELECT * FROM book_info b
        where b.id = '$id'";
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
        $name = $_POST['reservename'];
        $phone = $_POST['phone'];
        $cdate = $_POST['cdate'];
        $datetime = date('Y-m-d H:i:s');
        $d = $_POST['id'];

         
        // Insert into log table
        $sqlLog = "INSERT INTO reserve_book (reserve_name, reserve_phone, reserve_date,book_id,reserve_datetime) 
                   VALUES ('$name', '$phone', '$cdate', '$d','$datetime')";

        $result = $conn->query($sqlLog);

        // Insert into log table
        $bookst = "update book_info set book_status = '01' where id = '$d' ";

        $resultst = $conn->query($bookst);

        

    


        if (!$result) {
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
        <div class="card-header" style="background-color: #9acd32; color:white;">
            <h5 >จองหนังสือ <?php echo  $row['title']; ?></h5>
        </div>
        <div class="card-body">
        <form method="POST" action="managereserves.php">
        <input type="text" class="form-control" name="id" id="id" value="<?php echo $row['id']; ?>" style="display: none;" readonly>


            <div class="form-group">
                <label for="title"><b>ชื่อหนังสือ: </b> <?php echo $row['title']; ?></label>
            </div>

            <div class="form-group">
                <label for="reservename"><b>ชื่อผู้ยืม: </b> </label>
                <input type="text" class="form-control" name="reservename" id="reservename" >
            </div>

            <div class="form-group">
                <label for="reservename"><b>เบอร์ติดต่อภายใน: </b> </label>
                <input type="text" class="form-control" name="phone" id="phone" >
            </div>

            <div class="form-group">
                <label for="start"><b>ต้องการยืมวันที่: </b></label>
                <div class="form-group">
                    <input type="date" class="form-control" id="datepicker" name="cdate" required>
                </div>
            </div>

            <button type="submit" name="registers" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
</div>
</div>
</body>
</html>
