<?php
session_start();
include_once 'dbconnect.php';  

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    try {
        // Prepare the SQL query to fetch first and last name
        $query = "SELECT firstname, lastname FROM users WHERE id = :user_id";
        
        // Prepare statement with PDO
        $stmt = $pdo->prepare($query);
        
        // Bind the user_id parameter to the query
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch the data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Successfully retrieved the user's details
            $_SESSION['firstname'] = htmlspecialchars($user['firstname']);
            $_SESSION['lastname'] = htmlspecialchars($user['lastname']);
        } else {
            // Handle the case when the user is not found (shouldn't happen if the session is valid)
            $_SESSION['firstname'] = 'Guest';
            $_SESSION['lastname'] = '';
        }
    } catch (PDOException $e) {
        // Handle any errors with the database query
        echo "<p>Error retrieving user details: " . $e->getMessage() . "</p>";
        $_SESSION['firstname'] = 'Guest';
        $_SESSION['lastname'] = '';
    }
} else {
    // If the user is not logged in
    $_SESSION['firstname'] = 'Guest';
    $_SESSION['lastname'] = '';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Note It!</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <script>
      function confirmLogout(event) {
        event.preventDefault();
        if (confirm("Are you sure you want to logout?")) {
          window.location.href = event.target.href;
        }
      }
    </script>
  </head>
  <body>
    <div class="container">
      <div class="sidebar">
        <div class="logos">
          <h1>Note <span>It!</span></h1>
        </div>
        <nav>
          <ul>
            <li><a href="#"><i class="fas fa-sticky-note"></i>All Notes</a></li>
            <li><a href="#"><i class="fas fa-heart"></i> Favorites</a></li>
            <li><a href="#"><i class="fas fa-box"></i> Archives</a></li>
            <li><a href="signin.php" onclick="confirmLogout(event)"><i class="fas fa-power-off"></i> Logout</a></li>
          </ul>
        </nav>

        <div class="user">
              <div class="status"></div>
              <?php
              if ($_SESSION['firstname'] && $_SESSION['lastname']) {
                  echo "<p>Hi " . htmlspecialchars($_SESSION['firstname']) . " " . htmlspecialchars($_SESSION['lastname']) . "!<br />Welcome back.</p>";
              } else {
                  echo "<p>Hi Guest!<br />Please log in.</p>";
              }
              ?>
          </div>
        
      </div>

      <div class="main-content">
        <header class="header">
          <h2>All Notes</h2>
          <div class="search">
            <input type="text" placeholder="Search" />
            <button class="add-note">
              <i class="fas fa-plus"></i> Add Notes
            </button>
          </div>
        </header>
        <div class="notes-grid">
          <div class="note-card">
            <h3>
              Notes 1
              <select>
                <option>....</option>
              </select>
            </h3>
            <p>
              This is a sample notes printed here.<br />
              <br />Testing Notes are printed here.....
            </p>
            <div class="note-footer">
              <span class="dot yellow"></span>
              <span class="note-date">March 03, 2024</span>
            </div>
          </div>
          <!-- Add more note cards here -->
        </div>
      </div>
    </div>
  </body>
</html>
