<?php
include 'db.php';

$message = "";
$message_type = "";

// Delete Student
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM students WHERE student_id = $id");

    header("Location: students.php");
    exit();
}


// Update Student
if (isset($_POST['update_student'])) {

    $id = intval($_POST['student_id']);

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "UPDATE students SET
            name = '$name',
            email = '$email',
            phone = '$phone'
            WHERE student_id = $id";

    if (mysqli_query($conn, $sql)) {

        $message = "Student updated successfully!";
        $message_type = "success";

    } else {

        $message = "Error updating student: " . mysqli_error($conn);
        $message_type = "error";
    }
}


// Add Student
if (isset($_POST['add_student'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "INSERT INTO students (name, email, phone)
            VALUES ('$name', '$email', '$phone')";

    if (mysqli_query($conn, $sql)) {

        $message = "Student added successfully!";
        $message_type = "success";

    } else {

        $message = "Error adding student: " . mysqli_error($conn);
        $message_type = "error";
    }
}


// Student to Edit
$edit_student = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $edit_query = mysqli_query(
        $conn,
        "SELECT * FROM students WHERE student_id = $id"
    );

    $edit_student = mysqli_fetch_assoc($edit_query);
}


// Get all students
$result = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY student_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Students</title>

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

        <h1>Manage Students</h1>

        <p>
            Add, edit, view and delete students.
        </p>

    </div>


    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- Add / Edit Form -->

    <div class="form-box">

        <?php if ($edit_student) { ?>

            <h2>✏️ Edit Student</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="student_id"
                    value="<?php echo $edit_student['student_id']; ?>"
                >

                <input
                    type="text"
                    name="name"
                    placeholder="Student Name"
                    value="<?php echo htmlspecialchars($edit_student['name']); ?>"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value="<?php echo htmlspecialchars($edit_student['email']); ?>"
                    required
                >

                <input
                    type="text"
                    name="phone"
                    placeholder="Phone Number"
                    value="<?php echo htmlspecialchars($edit_student['phone']); ?>"
                    required
                >

                <button type="submit" name="update_student">
                    Update Student
                </button>

                <a class="cancel" href="students.php">
                    Cancel
                </a>

            </form>

        <?php } else { ?>

            <h2>➕ Add New Student</h2>

            <form method="POST">

                <input
                    type="text"
                    name="name"
                    placeholder="Student Name"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    required
                >

                <input
                    type="text"
                    name="phone"
                    placeholder="Phone Number"
                    required
                >

                <button type="submit" name="add_student">
                    Add Student
                </button>

            </form>

        <?php } ?>

    </div>


    <!-- Students Table -->

    <div class="table-box">

        <h2>👨‍🎓 All Students</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>

            </tr>


            <?php while ($student = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $student['student_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student['name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student['phone']); ?>
                    </td>

                    <td>

                        <a
                            class="edit"
                            href="students.php?edit=<?php echo $student['student_id']; ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete"
                            href="students.php?delete=<?php echo $student['student_id']; ?>"
                            onclick="return confirm('Delete this student?');"
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