 <?php
 // Connect to MySQL database
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "library";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                ?>