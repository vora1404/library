<?php
$sessionTimeout = 3600;

// Set the session.gc_maxlifetime configuration option
ini_set('session.gc_maxlifetime', $sessionTimeout);

// Set the session cookie lifetime
session_set_cookie_params($sessionTimeout);

// Start the session
session_start();

// Check the last activity time in the session
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessionTimeout)) {
    // Session has been inactive for too long, destroy it and redirect to login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update the last activity time in the session on every request
$_SESSION['LAST_ACTIVITY'] = time();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Access the user's information from the session
$userID = $_SESSION["user_id"];
$username = $_SESSION["username"];
$userRole = $_SESSION["user_role"];

// การเชื่อมต่อฐานข้อมูล MySQL
require_once "connect.php";

    date_default_timezone_set("Asia/Bangkok");



    $sql = "SELECT * FROM reserve_book rb 
    left join book_info b on rb.book_id = b.id
    left join category c on b.category_id = c.id";
    $result = $conn->query($sql);   
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

     // Update reservation data
     if (isset($_POST['accept'])) {
       
        $d = $_POST['reserve_id']; 
        // Insert into log table
        $bookst = "UPDATE reserve_book set approve = 'Y' where reserve_id = '$d'";


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

<!-- ส่วนของ HTML และ JavaScript สำหรับ DataTable -->
<!DOCTYPE html>

<head>
	<title>จองหนังสือ</title>
	<!-- นำเข้าไฟล์ CSS ของ Bootstrap 4 -->
 
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap">
    <link rel="stylesheet" href="/libra/styles.css">
    <!-- Load jQuery library and DataTables with Buttons extension -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>   
	<script type="text/javascript">
		$(document).ready( function () {
			$('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                'csv', 'excel', 'pdf', 'print'
                ],
               
			});
		});
	</script>
    
</head>


<body>
    
<div class="container">
  
    <!-- Add Bootstrap JS dependencies -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function openPopup(url) {
            window.open(url, "_blank", "width=800,height=800");
        }
    </script>
    <br/>

    <br/> 
	<!-- สร้างตาราง HTML สำหรับ DataTable -->

    <div class="container">

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-sm " onclick="openPopup('admin_book.php')">
                <i class="fas fa-plus"></i> รายการหนังสือทั้งหมด
            </button>
            <button type="button" class="btn btn-info btn-sm ml-2" onclick="openPopup('upload.php')">
                <b>เพิ่มหนังสือ</b>
            </button>
        </div>
    
        <div class="container"> 
            <h1>รายการจองยืมหนังสือ</h1>
            <br/>
            <hr>
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                    <tr>

                        <th>รายการ</th>
                        <th>หนังสือ</th>
                        <th>ประเภท</th>
                        <th>ผู้จอง</th>
                        <th>เบอร์ติดต่อ</th>
                        <th>วันที่จอง</th>
                        <th>สถานะ</th> 

                    </tr>
                </thead>
                <tbody>
                <?php
                    $rowcount = 1;
                    while($row = mysqli_fetch_array($result)) 
                    {
                ?> 
                    <tr>
                        <td><?php echo $rowcount; ?></td>
                        <td><?php echo $row["title"]; ?></td> 
                        <td><?php echo $row["category"]; ?></td> 
                        <td><?php echo $row["reserve_name"]; ?></td>
                        <td><?php echo $row["reserve_phone"]; ?></td>  
                        <td><?php echo $row["reserve_date"]; ?></td>
                        <td>
                            <?php
                            if ($row["book_status"] === "01") {
                                echo '<span class="badge badge-danger" onclick="openPopup(\'managebook.php?id=' . $row['reserve_id'] . '\');">' . 'รออนุมัติ' . '</span>';
                            } else if ($row["book_status"] === "02") {
                                echo '<span class="badge badge-warning" onclick="openPopup(\'managebook.php?id=' . $row['reserve_id'] . '\');">' . 'อยู่ระหว่างการยืม' . '</span>';
                            } else {
                                echo '<span class="badge badge-success" onclick="openPopup(\'managebook.php?id=' . $row['reserve_id'] . '\');">' . 'คืนแล้ว' . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                $rowcount++;
                }
                ?>
                </tbody>
            </table>
            </div>
    </div>  
</div>    
</body>


<?php
$conn->close();
?>

