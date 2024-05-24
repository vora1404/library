<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
</head>
<body>
    <h2>Upload Image</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="image">
        <input type="submit" value="Upload">
    </form>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        // Connect to database (replace values with your actual database credentials)

        echo "Debugging Info:<br>";
    echo "File name: " . $_FILES["image"]["name"] . "<br>";
    echo "File type: " . $_FILES["image"]["type"] . "<br>";
    echo "File size: " . $_FILES["image"]["size"] . " bytes<br>";
    echo "Temp file: " . $_FILES["image"]["tmp_name"] . "<br>";

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

      // Read image file
      $fileTmpName = $_FILES["image"]["tmp_name"];
      $fileHandle = fopen($fileTmpName, "rb"); // Open file in binary mode
      $imageData = fread($fileHandle, filesize($fileTmpName)); // Read entire file content
      fclose($fileHandle); // Close file handle
  
      // Debugging: Output length of image data
      echo "Length of image data: " . strlen($imageData) . " bytes<br>";
  
      // Prepare SQL query with image data directly included
      $sql = "INSERT INTO book_info (cover) VALUES ('$imageData')";
  
      // Execute SQL query
      if ($conn->query($sql) === TRUE) {
          echo "Image uploaded successfully.";
      } else {
          echo "Error uploading image: " . $conn->error;
      }
  
      // Close connection
      $conn->close();
    }
    ?>
</body>
</html>
