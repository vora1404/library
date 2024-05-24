<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Cover Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap">
</head>
<body>


    <div class="container">
    <h1>Upload Cover Page</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" required><br><br>
        
        <label for="category">Category:</label><br>
        <select id="category" name="category" required>
            <option value="1">Fiction</option>
            <option value="2">Non-fiction</option>
            <!-- Add more options as needed -->
        </select><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>
        
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        
        <label for="cover">Choose a cover image:</label><br>
        <input type="file" id="cover" name="cover" accept="image/*" required><br><br>
        
        <input type="submit" value="Upload">
    </form>
    </div>

    <?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1); // Display errors should be set to 0 in production

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO book_info (title, author, category_id, description, price, cover) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Check if the statement preparation was successful
    if (!$stmt) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("ssisbsi", $title, $author, $category_id, $description, $price, $cover_data);

    // Set parameters and execute
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $book_status = '1';

    $cover = $_FILES['cover']['tmp_name'];

    // Check for upload errors
    if ($_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $_FILES['cover']['error']);
    }

    // Read the cover image data
    $cover_data = file_get_contents($cover);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


</body>
</html>
