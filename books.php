<?php
include 'db.php';

$message = "";
$message_type = "";

// Delete Book
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM books WHERE book_id = $id");

    header("Location: books.php");
    exit();
}


// Edit Book
if (isset($_POST['update_book'])) {

    $id = intval($_POST['book_id']);

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = intval($_POST['quantity']);

    // Get old quantity and available quantity
    $old_query = mysqli_query(
        $conn,
        "SELECT quantity, available FROM books WHERE book_id = $id"
    );

    $old_book = mysqli_fetch_assoc($old_query);

    if (!$old_book) {

        $message = "Book not found!";
        $message_type = "error";

    } else {

        /*
         * Calculate how many books are currently issued.
         */
        $issued = $old_book['quantity'] - $old_book['available'];

        if ($quantity < $issued) {

            $message = "Quantity cannot be less than currently issued books!";
            $message_type = "error";

        } else {

            $new_available = $quantity - $issued;

            $sql = "UPDATE books SET
                    title = '$title',
                    author = '$author',
                    category = '$category',
                    quantity = $quantity,
                    available = $new_available
                    WHERE book_id = $id";

            if (mysqli_query($conn, $sql)) {

                $message = "Book updated successfully!";
                $message_type = "success";

            } else {

                $message = "Error updating book: " . mysqli_error($conn);
                $message_type = "error";
            }
        }
    }
}


// Add Book
if (isset($_POST['add_book'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = intval($_POST['quantity']);

    $sql = "INSERT INTO books
            (title, author, category, quantity, available)
            VALUES
            ('$title', '$author', '$category', $quantity, $quantity)";

    if (mysqli_query($conn, $sql)) {

        $message = "Book added successfully!";
        $message_type = "success";

    } else {

        $message = "Error adding book: " . mysqli_error($conn);
        $message_type = "error";
    }
}


// Get book for editing
$edit_book = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $edit_query = mysqli_query(
        $conn,
        "SELECT * FROM books WHERE book_id = $id"
    );

    $edit_book = mysqli_fetch_assoc($edit_query);
}


// Get all books
$result = mysqli_query(
    $conn,
    "SELECT * FROM books ORDER BY book_id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Books</title>

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
            padding: 35px;
        }

        .page-title {
            margin-bottom: 25px;
        }

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

        .form-box {
            background: white;
            padding: 25px;
            max-width: 550px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 35px;
        }

        .form-box h2 {
            margin-top: 0;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #444;
        }

        .cancel {
            display: block;
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            background: #ddd;
            color: #222;
            text-decoration: none;
            border-radius: 5px;
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

        .edit {
            background: #3498db;
            color: white;
            padding: 7px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 5px;
        }

        .delete {
            background: #e74c3c;
            color: white;
            padding: 7px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .edit:hover {
            background: #2980b9;
        }

        .delete:hover {
            background: #c0392b;
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

        <h1>Manage Books</h1>

        <p>
            Add, edit, view and delete library books.
        </p>

    </div>


    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- Add / Edit Form -->

    <div class="form-box">

        <?php if ($edit_book) { ?>

            <h2>✏️ Edit Book</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="book_id"
                    value="<?php echo $edit_book['book_id']; ?>"
                >

                <input
                    type="text"
                    name="title"
                    placeholder="Book Title"
                    value="<?php echo htmlspecialchars($edit_book['title']); ?>"
                    required
                >

                <input
                    type="text"
                    name="author"
                    placeholder="Author"
                    value="<?php echo htmlspecialchars($edit_book['author']); ?>"
                    required
                >

                <input
                    type="text"
                    name="category"
                    placeholder="Category"
                    value="<?php echo htmlspecialchars($edit_book['category']); ?>"
                >

                <input
                    type="number"
                    name="quantity"
                    placeholder="Quantity"
                    min="1"
                    value="<?php echo $edit_book['quantity']; ?>"
                    required
                >

                <button type="submit" name="update_book">
                    Update Book
                </button>

                <a class="cancel" href="books.php">
                    Cancel
                </a>

            </form>

        <?php } else { ?>

            <h2>➕ Add New Book</h2>

            <form method="POST">

                <input
                    type="text"
                    name="title"
                    placeholder="Book Title"
                    required
                >

                <input
                    type="text"
                    name="author"
                    placeholder="Author"
                    required
                >

                <input
                    type="text"
                    name="category"
                    placeholder="Category"
                >

                <input
                    type="number"
                    name="quantity"
                    placeholder="Quantity"
                    min="1"
                    required
                >

                <button type="submit" name="add_book">
                    Add Book
                </button>

            </form>

        <?php } ?>

    </div>


    <!-- Books Table -->

    <div class="table-box">

        <h2>📖 All Books</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Available</th>
                <th>Action</th>

            </tr>


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

                    <td>
                        <?php echo $book['available']; ?>
                    </td>

                    <td>

                        <a
                            class="edit"
                            href="books.php?edit=<?php echo $book['book_id']; ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete"
                            href="books.php?delete=<?php echo $book['book_id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this book?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>