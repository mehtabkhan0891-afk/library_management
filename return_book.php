<?php
include 'db.php';

$message = "";
$message_type = "";

if (isset($_POST['return_book'])) {

    $issue_id = intval($_POST['issue_id']);

    // Get issued book details
    $query = mysqli_query(
        $conn,
        "SELECT * FROM issue_books WHERE issue_id = $issue_id"
    );

    $issue = mysqli_fetch_assoc($query);

    if (!$issue) {

        $message = "Issue record not found!";
        $message_type = "error";

    } elseif ($issue['return_date'] != NULL) {

        $message = "This book is already returned!";
        $message_type = "error";

    } else {

        $book_id = $issue['book_id'];
        $return_date = date("Y-m-d");

        // Update return date
        $update_issue = mysqli_query(
            $conn,
            "UPDATE issue_books
             SET return_date = '$return_date'
             WHERE issue_id = $issue_id"
        );

        if ($update_issue) {

            // Increase available quantity
            mysqli_query(
                $conn,
                "UPDATE books
                 SET available = available + 1
                 WHERE book_id = $book_id"
            );

            $message = "Book returned successfully!";
            $message_type = "success";

        } else {

            $message = "Error returning book: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}

// Get currently issued books
$issued_books = mysqli_query(
    $conn,
    "SELECT issue_books.issue_id,
            students.name,
            books.title,
            issue_books.issue_date
     FROM issue_books
     JOIN students
     ON issue_books.student_id = students.student_id
     JOIN books
     ON issue_books.book_id = books.book_id
     WHERE issue_books.return_date IS NULL
     ORDER BY issue_books.issue_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Return Book</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        /* Navbar */

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

        /* Main */

        .container {
            padding: 40px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        /* Message */

        .message {
            max-width: 600px;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        /* Return Box */

        .return-box {
            background: white;
            max-width: 600px;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .return-box h2 {
            margin-top: 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        select {
            width: 100%;
            padding: 12px;

            border: 1px solid #ccc;
            border-radius: 5px;

            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 13px;

            margin-top: 25px;

            background: #222;
            color: white;

            border: none;
            border-radius: 5px;

            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #444;
        }

    </style>

</head>

<body>

<!-- Navbar -->

<div class="navbar">

    <h2>📚 Library Management System</h2>

    <a class="back" href="dashboard.php">
        ← Dashboard
    </a>

</div>


<!-- Main Container -->

<div class="container">

    <div class="page-title">

        <h1>📗 Return Book</h1>

        <p>Return a book currently issued to a student.</p>

    </div>


    <!-- Message -->

    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- Return Form -->

    <div class="return-box">

        <h2>Return an Issued Book</h2>

        <form method="POST">

            <label>Select Issued Book</label>

            <select name="issue_id" required>

                <option value="">
                    -- Select Book --
                </option>

                <?php while ($row = mysqli_fetch_assoc($issued_books)) { ?>

                    <option value="<?php echo $row['issue_id']; ?>">

                        <?php echo htmlspecialchars($row['title']); ?>

                        -
                        <?php echo htmlspecialchars($row['name']); ?>

                        -
                        Issued:
                        <?php echo $row['issue_date']; ?>

                    </option>

                <?php } ?>

            </select>


            <button type="submit" name="return_book">

                📗 Return Book

            </button>

        </form>

    </div>

</div>

</body>

</html>