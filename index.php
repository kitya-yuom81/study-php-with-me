<?php
// ---------- storage ----------
$file = __DIR__ . "/tasks.json";
$tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (!is_array($tasks)) $tasks = [];

// ---------- actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_task'])) {
    $text = trim($_POST['new_task']);
    if ($text !== '') {
        $tasks[] = ["text" => $text, "done" => false];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php"); exit;
}

if (isset($_GET['toggle'])) {
    $i = (int)$_GET['toggle'];
    if (isset($tasks[$i])) {
        $tasks[$i]['done'] = !$tasks[$i]['done'];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php"); exit;
}

if (isset($_GET['delete'])) {
    $i = (int)$_GET['delete'];
    if (isset($tasks[$i])) {
        array_splice($tasks, $i, 1);
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php"); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My To-Do List</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    background:#f5f6fb;
    min-height:100vh;
    display:grid;
    place-items:center;
    padding:24px;
  }
  .card {
    width:min(680px, 92vw);
    background:#fff;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    padding:22px 22px 10px;
  }
  h2 {
    margin: 4px 0 18px;
    font-size: 26px;
    display:flex; gap:10px; align-items:center;
  }
  h2 span { font-size:22px }
  form {
    display:flex; gap:10px; align-items:center;
    margin-bottom:14px;
  }
  input[type="text"] {
    flex:1;
    padding:12px 14px;
    border:1px solid #d7dbe7;
    border-radius:10px;
    font-size:16px;
    outline:none;
  }
  input[type="text"]:focus { border-color:#70c387; box-shadow:0 0 0 3px #e5f6ea; }
  button {
    padding:12px 18px;
    border:0; border-radius:10px;
    background:#22a652; color:#fff; font-weight:600; cursor:pointer;
  }
  button:hover { filter:brightness(0.95); }
  ul { list-style:none; padding:0; margin:0; }
  li {
    display:flex; justify-content:space-between; align-items:center;
    padding:10px 6px; border-top:1px solid #f0f2f7;
  }
  .text { display:flex; align-items:center; gap:10px; }
  .text.done { color:#8b8f9c; text-decoration:line-through; }
  .actions { display:flex; gap:10px; }
  .a-btn { text-decoration:none; font-size:14px; padding:6px 10px; border-radius:8px; }
  .toggle { background:#eef6ff; color:#1766d1; }
  .delete { background:#ffeef0; color:#d11a2a; }
  .empty { color:#9aa3b2; padding:14px 6px; text-align:center; }
</style>
</head>
<body>
  <div class="card">
    <h2>✅ <span>My To-Do List</span></h2>

    <form method="post" autocomplete="off">
      <input type="text" name="new_task" placeholder="Enter new task">
      <button type="submit">Add</button>
    </form>

    <?php if (count($tasks) === 0): ?>
      <div class="empty">No tasks yet — add your first one!</div>
    <?php else: ?>
      <ul>
        <?php foreach ($tasks as $i => $task): ?>
          <li>
            <div class="text <?= $task['done'] ? 'done' : '' ?>">
              <?= htmlspecialchars($task['text']) ?>
            </div>
            <div class="actions">
              <a class="a-btn toggle" href="?toggle=<?= $i ?>">✔ Done</a>
              <a class="a-btn delete" href="?delete=<?= $i ?>" onclick="return confirm('Delete this task?')">✖ Delete</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</body>
</html>
