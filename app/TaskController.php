<?php
namespace App;

final class TaskController {
    private TaskModel $model;
    public function __construct() { $this->model = new TaskModel(); }

    public function index(): void {
        $filter = $_GET['filter'] ?? 'all'; // all | active | completed
        $tasks  = $this->model->all();
        $filtered = match ($filter) {
            'active' => array_values(array_filter($tasks, fn($t) => !$t['done'])),
            'completed' => array_values(array_filter($tasks, fn($t) => $t['done'])),
            default => $tasks,
        };
        render('tasks/list', [
            'tasks' => $filtered,
            'counts' => [
                'all' => count($tasks),
                'active' => count(array_filter($tasks, fn($t)=>!$t['done'])),
                'completed' => count(array_filter($tasks, fn($t)=>$t['done'])),
            ],
            'filter' => $filter,
            'csrf' => csrf_token(),
        ]);
    }

    public function create(): void {
        if (!csrf_ok()) redirect('/');
        $text = trim($_POST['text'] ?? '');
        if ($text !== '') $this->model->add($text);
        redirect('/');
    }

    public function toggle(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->toggle($_POST['id'] ?? '');
        redirect('/');
    }

    public function delete(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->delete($_POST['id'] ?? '');
        redirect('/');
    }

    public function update(): void {
        if (!csrf_ok()) redirect('/');
        $text = trim($_POST['text'] ?? '');
        if ($text !== '') $this->model->update($_POST['id'] ?? '', $text);
        redirect('/');
    }

    public function clearCompleted(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->clearCompleted();
        redirect('/');
    }
}
