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
        <form action="index.php" method="get" class="add-form">
            <label for='title'>Title</label>
            <input type="text" name="title" placeholder="Enter task title" required>
            <label for='description'>Description</label>
            <input type="text" name="description" placeholder="Enter task description" required>
            <button type="submit" name="addTask" value="add">Add task</button>
        </form>
    </div>
    <br>
   
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
                $Task = null; 
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
    <div class="task-list-container">

        
        <? 
            $tasks_array = getTasks();
            $tasks = $tasks_array['unfinished'];

            $title = "To do:";
            require 'task-list.php'; 
        ?>
        <? 
            $title = "Completed tasks!";
            $tasks = $tasks_array['completed'];
            require 'task-list.php'; 
        ?>
        

    </div>

        <? dbDisconnect(); ?>
</body>

</html>