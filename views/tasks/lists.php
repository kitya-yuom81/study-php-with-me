<?php /** @var array $tasks */ /** @var array $counts */ /** @var string $filter */ /** @var string $csrf */ ?>
<div class="card">
  <header class="header">
    <h2>✅ My To-Do List</h2>
    <nav class="filters">
      <a class="<?= $filter==='all'?'active':'' ?>" href="/?filter=all">All (<?= $counts['all'] ?>)</a>
      <a class="<?= $filter==='active'?'active':'' ?>" href="/?filter=active">Active (<?= $counts['active'] ?>)</a>
      <a class="<?= $filter==='completed'?'active':'' ?>" href="/?filter=completed">Completed (<?= $counts['completed'] ?>)</a>
    </nav>
  </header>

  <form class="add-form" method="post" action="/task/create">
    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
    <input type="text" name="text" placeholder="Add a new task…" autocomplete="off">
    <button type="submit">Add</button>
  </form>

  <?php if (empty($tasks)): ?>
    <div class="empty">No tasks here. Add one above 👆</div>
  <?php else: ?>
    <ul class="list">
      <?php foreach ($tasks as $t): ?>
        <li class="row <?= $t['done'] ? 'done' : '' ?>">
          <form method="post" action="/task/toggle">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
            <button class="pill" title="Toggle">✔</button>
          </form>

          <form class="text-form" method="post" action="/task/update">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
            <input class="text-input" name="text" value="<?= htmlspecialchars($t['text']) ?>">
            <button class="save">Save</button>
          </form>

          <form method="post" action="/task/delete" onsubmit="return confirm('Delete this task?')">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
            <button class="pill danger" title="Delete">✖</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
    <form class="footer" method="post" action="/task/clear-completed">
      <input type="hidden" name="_csrf" value="<?= $csrf ?>">
      <button class="ghost">Clear completed</button>
    </form>
  <?php endif; ?>
</div>
