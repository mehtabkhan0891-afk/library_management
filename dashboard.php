<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// Total Books
$query = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM books");
$row = mysqli_fetch_assoc($query);
$total_books = $row['total'] ?? 0;

// Total Students
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$row = mysqli_fetch_assoc($query);
$total_students = $row['total'] ?? 0;

// Issued Books
$query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM issue_books WHERE return_date IS NULL"
);
$row = mysqli_fetch_assoc($query);
$issued_books = $row['total'] ?? 0;

// Available Books
$query = mysqli_query($conn, "SELECT SUM(available) AS total FROM books");
$row = mysqli_fetch_assoc($query);
$available_books = $row['total'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Library Management Dashboard</title>

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

        .logout {
            color: white;
            text-decoration: none;
            background: #e74c3c;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .logout:hover {
            background: #c0392b;
        }

        /* Main Container */
        .container {
            padding: 40px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            margin-bottom: 10px;
        }

        /* Statistics */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            margin: 0 0 15px;
            font-size: 18px;
        }

        .stat-card p {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }

        /* Menu Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            margin-bottom: 15px;
        }

        .card p {
            color: #666;
            margin-bottom: 20px;
        }

        .card a {
            display: inline-block;
            text-decoration: none;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .card a:hover {
            background: #555;
        }

        /* Responsive */
        @media (max-width: 800px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">

        <h2>📚 Library Management System</h2>

        <a class="logout" href="logout.php">
            Logout
        </a>

    </div>


    <!-- Main Container -->
    <div class="container">

        <!-- Welcome -->
        <div class="welcome">

            <h1>Admin Dashboard</h1>

            <p>
                Welcome,
                <b><?php echo htmlspecialchars($_SESSION["admin"]); ?></b>!
            </p>

        </div>


        <!-- Statistics -->
        <div class="stats">

            <div class="stat-card">

                <h3>📚 Total Books</h3>

                <p>
                    <?php echo $total_books; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>👨‍🎓 Students</h3>

                <p>
                    <?php echo $total_students; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>📕 Issued Books</h3>

                <p>
                    <?php echo $issued_books; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>✅ Available Books</h3>

                <p>
                    <?php echo $available_books; ?>
                </p>

            </div>

        </div>


        <!-- Menu Cards -->
        <div class="cards">

            <!-- Manage Books -->
            <div class="card">

                <h3>📖 Manage Books</h3>

                <p>
                    Add and manage library books.
                </p>

                <a href="books.php">
                    Open
                </a>

            </div>


            <!-- Search Books -->
            <div class="card">

                <h3>🔍 Search Books</h3>

                <p>
                    Search books by title, author or category.
                </p>

                <a href="search.php">
                    Open
                </a>

            </div>


            <!-- Manage Students -->
            <div class="card">

                <h3>👨‍🎓 Manage Students</h3>

                <p>
                    Add and manage students.
                </p>

                <a href="students.php">
                    Open
                </a>

            </div>


            <!-- Issue Book -->
            <div class="card">

                <h3>📕 Issue Book</h3>

                <p>
                    Issue books to students.
                </p>

                <a href="issue_book.php">
                    Open
                </a>

            </div>


            <!-- Return Book -->
            <div class="card">

                <h3>📗 Return Book</h3>

                <p>
                    Return issued books.
                </p>

                <a href="return_book.php">
                    Open
                </a>
                <div class="card">

    <h3>📋 Issue History</h3>

    <p>
        View all issued and returned books.
    </p>

    <a href="history.php">
        Open
    </a>

</div>

            </div>

        </div>

    </div>

</body>

</html>