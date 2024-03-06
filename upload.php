<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Cover Page</title>
</head>
<body>
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

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $title = $_POST['title'];
        $author = $_POST['author'];
        $category_id = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $cover = file_get_contents($_FILES['cover']['tmp_name']);

        $stmt = $conn->prepare("INSERT INTO book_info (title, author, category_id, description, price, cover) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisdb", $title, $author, $category_id, $description, $price, $cover);

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
