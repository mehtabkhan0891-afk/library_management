<?php
include 'db.php';

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$sql = "SELECT * FROM books
        WHERE title LIKE '%$search%'
        OR author LIKE '%$search%'
        OR category LIKE '%$search%'
        ORDER BY book_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background: #222;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .back {
            color: white;
            text-decoration: none;
            background: #444;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .container {
            padding: 40px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .search-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        .search-form button {
            padding: 13px 25px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #444;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #222;
            color: white;
            padding: 14px;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .available {
            font-weight: bold;
        }

        .not-found {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>📚 Library Management System</h2>
    <a class="back" href="dashboard.php">← Dashboard</a>
</div>

<div class="container">

    <div class="page-title">
        <h1>🔍 Search Books</h1>
        <p>Search books by title, author or category.</p>
    </div>

    <div class="search-box">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search by title, author or category..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">
                🔍 Search
            </button>

        </form>

    </div>

    <div class="table-box">

        <h2>📖 Book Results</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Available</th>
            </tr>

            <?php if (mysqli_num_rows($result) > 0) { ?>

                <?php while ($book = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?php echo $book['book_id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['author']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['category']); ?>
                        </td>

                        <td>
                            <?php echo $book['quantity']; ?>
                        </td>

                        <td class="available">
                            <?php echo $book['available']; ?>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="6" class="not-found">
                        No books found.
                    </td>
                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>