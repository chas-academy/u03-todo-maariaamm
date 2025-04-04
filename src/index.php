<?php
require_once 'Tasks.php';
require_once 'header.php';

// Kontrollerar om formuläret har skickats
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $task_id = htmlspecialchars($_POST['task_id']);

    // Lägger till uppgiften i databasen
    addTask(['task_id' => $_GET['task_id'], 'title' => $title, 'description' => $description]);
}
?>
<html>

<body>
    <h2>My to-do list!</h2>
    <div class="add-task">
        <form action="index.php" method="get">
            <input type="text" name="title" placeholder="Enter task title" required>
            <input type="text" name="description" placeholder="Enter task description" required>
            <button type="submit" name="addTask" value="add">Add task</button>
        </form>
    </div>
    <br>
    <ul>
        <?php
        if (isset($_GET['addTask'])) {
            if ($_GET['addTask'] == 'add') {
                addTask([
                    'task_id' => htmlspecialchars($_GET['task_id']),
                    'title' => htmlspecialchars($_GET['title']),
                    'description' => htmlspecialchars($_GET['description'])
                ]);
            }
        ?>
            <br>
            <?php
            if (isset($_GET['addTask']) && $_GET['addTask'] === "edit") {
                if (isset($_GET['task_id']) && is_numeric($_GET['task_id'])) {
                    $Task = getTaskbyid(htmlspecialchars($_GET['task_id']));
                } else {
                    echo "<p>Error: Invalid Task ID.</p>";
                    $Task = null; // Säkerhetsåtgärd
                }

                if (!empty($Task)) {
            ?>
                    <h3>Edit task</h3>
                    <div class="edit-box">
                        <form action="Tasks.php" method="POST">
                            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($Task['task_id']); ?>">
                            <input type="text" name="title" value="<?php echo htmlspecialchars($Task['title']); ?>" required>
                            <br>
                            <input type="text" name="description" value="<?php echo htmlspecialchars($Task['description']); ?>" required>
                            <br>
                            <button type="submit" name="addTask" value="edit">Save</button>
                        </form>
                    </div>
            <?php
                }
            }
            ?>

        <?php
        }
        ?>
        <?php
        $Tasks = getTasks();
        if (!$Tasks) {
            echo "<p>All tasks are completed! Add a new task above!</p>";
        } else {
            echo "<p>All my tasks</p>"; // Flytta utanför loopen för att undvika upprepning
            echo "<ul>";
            foreach ($Tasks as $Task) {
                // Kontrollera om 'task_id', 'title' och 'description' är definierade innan de används
                if (isset($Task['task_id'], $Task['title'], $Task['description'])) {
                    echo "<li>" . htmlspecialchars($Task['title']) . " - " . htmlspecialchars($Task['description']) . "</li>";
                    echo "<form action='index.php' method='get'> 
                        <input type='hidden' name='task_id' value='" . htmlspecialchars($Task['task_id']) . "'> 
                        <button type='submit' name='addTask' value='edit'>Edit</button>
                      </form>";
                    echo "<form action='Tasks.php' method='post'> 
                        <input type='hidden' name='task_id' value='" . htmlspecialchars($Task['task_id']) . "'> 
                        <button type='submit' name='addTask' value='delete'>Delete</button>
                      </form>";
                } else {
                    echo "<li>Invalid task data.</li>";
                }
            }
            echo "</ul>";
        }
        ?>
</body>

</html>