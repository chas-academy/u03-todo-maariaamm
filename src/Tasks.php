<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

dbConnect();

function getTasks()
{
    global $conn;

    try {
        $data = $conn->query("SELECT * FROM Tasks")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    } catch (PDOException $e) {
        die("Error fetching tasks: " . $e->getMessage());
    }
}
function getTaskbyid($task_id) {
    global $conn;

    $data = $conn->prepare("SELECT * FROM Tasks WHERE task_id = :task_id")->fetchAll(PDO::FETCH_ASSOC);
    return $data;
}


function addTask($taskData)
{
    global $conn;

    $query = $conn->prepare("INSERT INTO Tasks (task_id, title, description) VALUES (:task_id, :title, :description)");
    $query->bindParam(":title", $taskData['title']);
    $query->bindParam(":description", $taskData['description']);
    $query->bindParam(":task_id", $taskData['task_id']);
    $query->execute();

    header('Location: http://localhost:8081/index.php');
    exit();
}
    function editTask($taskData) {
    global $conn;
     
        $query = $conn->prepare("UPDATE Tasks SET title = :title, description = :description WHERE task_id = :task_id");
        $query->bindParam(":title", $taskData['title']);
        $query->bindParam(":description", $taskData['description']);
        $query->bindParam(":task_id", $taskData['task_id']);
        
        $query->execute();

        header('Location: http://localhost:8081/index.php');
        exit();
    }
if (isset($_POST['addTask'])) {
    if ($_POST['addTask'] == 'add') {
        addTask(['title' => $_POST['title'], 'description' => $_POST['description']]);
    } else if ($_POST['addTask'] == 'edit') {
        editTask(['task_id' => $_POST['task_id'], 'title' => $_POST ['title'], 'description' => $_POST['description'], 'task_id' => $_POST['task_id']]);
    }
}
