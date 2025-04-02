<?php
require_once 'Tasks.php';

// Kontrollerar om formuläret har skickats
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);

    // Lägger till uppgiften i databasen
    addTask(['title' => $title, 'description' => $description]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Create task form -->

    <form action="index.php" method="get">
        <input type="text" name="title" placeholder="Enter task title" required>
        <input type="text" name="description" placeholder="Enter task description" required>
        <button type="submit" name="addTask" value="add">Add task</button>
    </form>
    <br>
    <ul>
        <?php
        if (isset($_GET['addTask'])) {
            if ($_GET['addTask'] == 'add') {
                addTask(['title' => $_GET['title'], 'description' => $_GET['description']]);
            }
        ?>
            <h2>My tasks:</h2>
            <form action="index.php" method="post">
                <input type="text" name="title" placeholder="Enter task title" required>
                <input type="text" name="description" placeholder="Enter task description" required>
                <button type="submit" name="addTask" value="edit">Add task</button>
            </form>
            <br>
        <?php
        } else if ($_GET['addTask'] == "edit") {
            $Task = getTaskbyid($_GET['task_id']);
        ?>
            <br>
                        <!-- Edit task form -->
            <h2>Edit task</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="task_id" value="<?php echo $Task['task_id'] ?>">

                <input type="text" name="title" value="<?php echo $Task['title'] ?>" required>
                <br>

                <input type="text" name="description" value="<?php echo $Task['description'] ?>" required>
                <br>

                <button type="submit" name="addTask" value="edit">Edit</button>
            </form>
        <?php
        }
        ?>
        <?php
            $Tasks = getTasks(); // Hämta alla uppgifter från databasen
        if (!$Tasks) {
            echo "<li>No tasks yet!</li>";
        } else {
            foreach ($Tasks as $Task) {
                echo "<li>" . htmlspecialchars($Task['title']) . " - " . htmlspecialchars($Task['description']) . "</li>";
                echo "<form action='index.php' method='get'> <input type='hidden' name='task_id' value='{$Task['task_id']}'> <button type='submit' name='addTask' value='edit'>Edit</button></form>";
            }
        }
        ?>
    </ul>
  
</body>

</html>