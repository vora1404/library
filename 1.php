<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books by Category</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        th, td {
            text-align: center;
            vertical-align: middle !important;
        }
        img.img-thumbnail {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mt-4 mb-4">Books by Category</h1>
    
    <table id="bookTable" class="table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="book1.jpg" alt="Book 1" class="img-thumbnail"></td>
                <td>Book 1 Title</td>
                <td>Author 1</td>
                <td>Fiction</td>
            </tr>
            <tr>
                <td><img src="book2.jpg" alt="Book 2" class="img-thumbnail"></td>
                <td>Book 2 Title</td>
                <td>Author 2</td>
                <td>Non-Fiction</td>
            </tr>
            <!-- Add more rows for other books -->
        </tbody>
    </table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bookTable').DataTable();
    });
</script>

</body>
</html>
