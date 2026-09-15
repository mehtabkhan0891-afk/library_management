<?php
include 'db.php';

$query = mysqli_query(
    $conn,
    "SELECT 
        issue_books.issue_id,
        students.name AS student_name,
        books.title AS book_title,
        issue_books.issue_date,
        issue_books.return_date
     FROM issue_books
     JOIN students 
        ON issue_books.student_id = students.student_id
     JOIN books 
        ON issue_books.book_id = books.book_id
     ORDER BY issue_books.issue_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Issue History</title>

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

        .table-box {
            background: white;
            padding: 25px;
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

        .issued {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
        }

        .returned {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 25px;
            color: #777;
        }
    </style>

</head>

<body>

    <div class="navbar">

        <h2>📚 Library Management System</h2>

        <a class="back" href="dashboard.php">
            ← Dashboard
        </a>

    </div>


    <div class="container">

        <div class="page-title">

            <h1>📋 Issue & Return History</h1>

            <p>
                View all books issued and returned by students.
            </p>

        </div>


        <div class="table-box">

            <h2>📖 Transaction History</h2>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Book</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>

                <?php if (mysqli_num_rows($query) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                        <tr>

                            <td>
                                <?php echo $row['issue_id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['student_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['book_title']); ?>
                            </td>

                            <td>
                                <?php echo $row['issue_date']; ?>
                            </td>

                            <td>
                                <?php
                                echo $row['return_date']
                                    ? $row['return_date']
                                    : '-';
                                ?>
                            </td>

                            <td>

                                <?php if ($row['return_date'] == NULL) { ?>

                                    <span class="issued">
                                        📕 Issued
                                    </span>

                                <?php } else { ?>

                                    <span class="returned">
                                        ✅ Returned
                                    </span>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6" class="empty">
                            No issue/return records found.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>

</html>