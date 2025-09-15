<?php
$file = "tasks.txt";

$tasks = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

if (isset($_POST['new_task']) && $_POST['new_task'] !== "") {
    $tasks[] = $_POST['new_task'];
    file_put_contents($file, implode("\n", $tasks));
    header("Location: index.php"); // refresh
    exit;
}

if (isset($_GET['delete'])) {
    unset($tasks[$_GET['delete']]);
    file_put_contents($file, implode("\n", $tasks));
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP To-Do List</title>
</head>
<body>
    <h2>My To-Do List</h2>

    <form method="POST">
        <input type="text" name="new_task" placeholder="Enter task">
        <button type="submit">Add</button>
    </form>

    <ul>
        <?php foreach ($tasks as $index => $task): ?>
            <li>
                <?= htmlspecialchars($task) ?>
                <a href="?delete=<?= $index ?>">❌</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
