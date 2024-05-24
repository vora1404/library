<?php 
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['categories']) && !empty($_POST['categories'])) {
        // Sanitize input to prevent SQL injection
        $categories = array_map('intval', $_POST['categories']);
        
        // Convert array to comma-separated string for SQL query
        $category_ids = implode(',', $categories);
        
        // Construct SQL query to filter books by selected categories
        $sql_books = "SELECT b.id,b.title,b.category_id,b.book_status FROM book_info b WHERE category_id IN ($category_ids)";

        // Query to retrieve the names of the selected categories
        $sql_selected_categories = "SELECT group_concat(category) as category FROM category WHERE id IN ($category_ids)";

    } else {
        // If no categories are selected, show all books
        $sql_books = "SELECT b.id,b.title,b.category_id,b.book_status FROM book_info b";
    }
} elseif (isset($_GET['search']) && !empty($_GET['search'])) {
    // If search query is provided, filter books by title
    $search_query = $_GET['search'];
    $sql_books = "SELECT b.id,b.title,b.category_id,b.book_status FROM book_info b
    WHERE b.title LIKE '%$search_query%'";
    $sql_selected_categories = "SELECT 'ทั้งหมด' as category FROM category limit 1";
} else {
    // If not a POST request and no search query, show all books
    $sql_books = "SELECT b.id,b.title,b.category_id,b.book_status FROM book_info b";
    $sql_selected_categories = "SELECT 'ทั้งหมด' as category FROM category limit 1";
}




// Execute SQL query
$result_books = $conn->query($sql_books);
$result_selected_categories = $conn->query($sql_selected_categories);



?>

<!DOCTYPE html>
<html>
<head>
    <title>ห้องสมุดรพ.พุทธโสธร</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap">
    <link rel="stylesheet" href="/library/styles.css">
</head>

<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
        <script>
            function openPopup(url) {
                window.open(url, "_blank", "width=800,height=800");
            }
        </script>
        <script>
            function validateForm() {
                var checkboxes = document.getElementsByName('categories[]');
                var isChecked = false;
                
                for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    break;
                }
                }
                
                if (!isChecked) {
                alert('Please select at least one category.');
                return false;
                }
                
                return true;
            }
        </script>
        <h1></h1>
        <br>
        <hr/>
        <div class="row">
       
            <div class="col-md-3 category-filter">
                <h6 style="color: #769DEA;">หมวดหนังสือ</h6>
            
                <form method="POST" action="" id="myForm" onsubmit="return validateForm()">
                    <?php
                    // Fetch categories from the database
                    $sql_categories = "SELECT * FROM category";
                    $result_categories = $conn->query($sql_categories);

                    // Display categories as checkboxes
                    if ($result_categories->num_rows > 0) {
                        while ($row_category = $result_categories->fetch_assoc()) {
                            echo '<div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="' . $row_category["id"] . '" id="category_' . $row_category["id"] . '" name="categories[]" >
                                    <label class="form-check-label" for="category_' . $row_category["id"] . '">
                                        ' . $row_category["category"] . '
                                    </label>
                                </div>';
                        }
                    } else {
                        echo "No categories found.";
                    }
                    ?>
                    </br>
                    <button type="submit" class="btn btn-primary">ตกลง</button>
                </form>
            </div>


            <div class="col-md-9">
                <form method="GET" action="">
                <div class="form-group row">
                    <label for="search" class="col-sm-2 col-form-label">ค้นหาหนังสือ: </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="search" name="search" placeholder="">
                    </div>

                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </div>
                
                </form>
                <?php
                if ($result_selected_categories->num_rows > 0) {
                    while ($row_selected_category = $result_selected_categories->fetch_assoc()) {
                        echo '<p style="color: #769DEA;">' . $row_selected_category['category'] . '</p>';
                    }
                } 
                ?>
               
                <div class="row book-container">
                    <?php
                    // Display book data
                    if ($result_books->num_rows > 0) {
                        $count = 0;
                        while ($row_book = $result_books->fetch_assoc()) {
                            if ($count % 5 == 0 && $count != 0) {
                                echo '</div><div class="row book-container">';
                            }
                            echo '<div class="book col-md-2">';
                            echo '<img src="/library/cover.jpg" alt="Description of the image" style="width: 120px; height: 150px;">';
                            echo '<p style="text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 120px;" title="' . $row_book["title"] . '">' . $row_book["title"] . '</p>';

                            echo '<p style="text-align: left;"></p>';
                            //echo '<span class="badge badge-success" onclick="openPopup(\'managereserves.php?id=' . $row_book['id'] . '\');">' . 'จอง' . '</span>';
                            // Check if the status is '09'
                            if ($row_book["book_status"] === "01" || $row_book["book_status"] === null) {
                                echo '<span class="badge badge-success" style="width: 100%; display: block;" onclick="openPopup(\'managereserves.php?id=' . $row_book['id'] . '\');">' . 'จอง' . '</span>';
                            } else {
                                echo '<span class="badge badge-danger" style="width: 100%; display: block;">' . 'จองแล้ว' . '</span>';
                            }
                            echo '</div>';
                            
                            $count++;
                        }
                    } else {
                        echo "No books found.";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
