<?php
include 'db.php';

$message = "";
$message_type = "";

if (isset($_POST['issue_book'])) {

    $student_id = intval($_POST['student_id']);
    $book_id = intval($_POST['book_id']);
    $issue_date = date("Y-m-d");

    // Check student
    $student_query = mysqli_query(
        $conn,
        "SELECT * FROM students WHERE student_id = $student_id"
    );

    if (mysqli_num_rows($student_query) == 0) {

        $message = "Invalid student selected!";
        $message_type = "error";

    } else {

        // Check book
        $book_query = mysqli_query(
            $conn,
            "SELECT * FROM books WHERE book_id = $book_id"
        );

        $book = mysqli_fetch_assoc($book_query);

        if (!$book) {

            $message = "Book not found!";
            $message_type = "error";

        } elseif ($book['available'] <= 0) {

            $message = "Book is not available!";
            $message_type = "error";

        } else {

            // Check whether same student already has this book
            $duplicate_query = mysqli_query(
                $conn,
                "SELECT * FROM issue_books
                 WHERE student_id = $student_id
                 AND book_id = $book_id
                 AND return_date IS NULL"
            );

            if (mysqli_num_rows($duplicate_query) > 0) {

                $message = "This student already has this book!";
                $message_type = "error";

            } else {

                $sql = "INSERT INTO issue_books
                        (student_id, book_id, issue_date)
                        VALUES
                        ($student_id, $book_id, '$issue_date')";

                if (mysqli_query($conn, $sql)) {

                    mysqli_query(
                        $conn,
                        "UPDATE books
                         SET available = available - 1
                         WHERE book_id = $book_id"
                    );

                    $message = "Book issued successfully!";
                    $message_type = "success";

                } else {

                    $message = "Error issuing book: " . mysqli_error($conn);
                    $message_type = "error";
                }
            }
        }
    }
}

$students = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY name ASC"
);

$books = mysqli_query(
    $conn,
    "SELECT * FROM books WHERE available > 0 ORDER BY title ASC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Issue Book</title>

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

        .message {
            max-width: 550px;
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

        .issue-box {
            background: white;
            max-width: 550px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .issue-box h2 {
            margin-top: 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 20px;
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

<div class="navbar">

    <h2>📚 Library Management System</h2>

    <a class="back" href="dashboard.php">
        ← Dashboard
    </a>

</div>


<div class="container">

    <div class="page-title">

        <h1>📕 Issue Book</h1>

        <p>
            Issue an available book to a student.
        </p>

    </div>


    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <div class="issue-box">

        <h2>Issue a Book</h2>

        <form method="POST">

            <label>Select Student</label>

            <select name="student_id" required>

                <option value="">
                    -- Select Student --
                </option>

                <?php while ($student = mysqli_fetch_assoc($students)) { ?>

                    <option value="<?php echo $student['student_id']; ?>">

                        <?php echo htmlspecialchars($student['name']); ?>

                    </option>

                <?php } ?>

            </select>


            <label>Select Book</label>

            <select name="book_id" required>

                <option value="">
                    -- Select Book --
                </option>

                <?php while ($book = mysqli_fetch_assoc($books)) { ?>

                    <option value="<?php echo $book['book_id']; ?>">

                        <?php echo htmlspecialchars($book['title']); ?>

                        - Available:
                        <?php echo $book['available']; ?>

                    </option>

                <?php } ?>

            </select>


            <button type="submit" name="issue_book">

                📕 Issue Book

            </button>

        </form>

    </div>

</div>

</body>

</html>