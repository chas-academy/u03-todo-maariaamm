<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

dbConnect();

/**
 * Hämtar alla tasks, separerar dem i "completed" och "unfinished"
 */
function getTasks()
{
    global $conn;

    try {
        $unfinished = $conn->query("SELECT * FROM Tasks WHERE task_status='0' ")->fetchAll(PDO::FETCH_ASSOC);
        $completed = $conn->query("SELECT * FROM Tasks WHERE task_status='1' ")->fetchAll(PDO::FETCH_ASSOC);
        return array(
            'unfinished' => $unfinished,
            'completed' => $completed
        );
    } catch (PDOException $e) {
        die("Error fetching tasks: " . $e->getMessage());
    }
}

/**
 * Hämtar en task med ett givet ID
 */
function getTaskbyid($task_id)
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM Tasks WHERE task_id = :task_id");
    $query->bindParam(":task_id", $task_id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC); // Hämta en enda rad istället för fetchAll
}


/**
 * Lägger till en task
 */
function addTask($taskData)
{
    global $conn;

    $query = $conn->prepare("INSERT INTO Tasks (title, description) VALUES (:title, :description)");
    $query->bindParam(":title", $taskData['title']);
    $query->bindParam(":description", $taskData['description']);
    $query->execute();

    header('Location: http://localhost:8081/index.php');
    exit();
}

/**
 * Ändrar en task
 */
function editTask($taskData)
{
    global $conn;

    $query = $conn->prepare("UPDATE Tasks SET title = :title, description = :description WHERE task_id = :task_id");
    $query->bindParam(":title", $taskData['title']);
    $query->bindParam(":description", $taskData['description']);
    $query->bindParam(":task_id", $taskData['task_id']);

    $query->execute();

    header('Location: http://localhost:8081/index.php');
    exit();
}

/**
 * Tar bort från databas
 */
function deleteTask($task_id)
{
    global $conn;
    if (!is_numeric($task_id)) {
        die("Invalid task ID");
    }
    $query = $conn->prepare("DELETE FROM Tasks WHERE task_id = :task_id");
    $query->bindParam(":task_id", $task_id, PDO::PARAM_INT);
    $query->execute();

    header('Location: http://localhost:8081/index.php');
    exit();
}

/**
 * Togglar mellan complete/uncomplete
 */
function updateTaskStatus($task_id)
{
    global $conn;

    $query = $conn->prepare("UPDATE Tasks
                SET task_status = 1 - task_status
                WHERE task_id = :task_id;");

    $query->bindParam(":task_id", $task_id, PDO::PARAM_INT);
    $query->execute();

    header('Location: http://localhost:8081/index.php');
    exit();
}

// Kontrollera om vi ska ändra status
if (isset($_POST['updateStatus'])) {
    updateTaskStatus($_POST['task_id']);
}

// sätt på "edit" mode för en task.
if (isset($_POST['editTask'])) {
    header('Location: http://localhost:8081/index.php?action=edit&task_id=' . $_POST['task_id']);
    exit();
}

if (isset($_POST['addTask'])) {
    if ($_POST['addTask'] == 'add') {
        addTask(['title' => $_POST['title'], 'description' => $_POST['description']]);
    } else if ($_POST['addTask'] == 'edit') {
        editTask(['task_id' => $_POST['task_id'], 'title' => $_POST['title'], 'description' => $_POST['description']]);
    } else if ($_POST['addTask'] == 'delete') {
        deleteTask($_POST['task_id']);
    }
}


// Spara ändringen i en uppgift till databasen
if (isset($_POST['editTaskSave'])) {
    editTask(['task_id' => $_POST['task_id'], 'title' => $_POST['title'], 'description' => $_POST['description']]);
}

// Ta bort en uppgift från databasen
if (isset($_POST['deleteTask'])) {
    deleteTask($_POST['task_id']);
}
