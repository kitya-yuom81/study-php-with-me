<?php
namespace App;

final class TaskController {
    private TaskModelSqlite $model;
    public function __construct() { $this->model = new TaskModelSqlite(); }

    public function index(): void {
        $filter = $_GET['filter'] ?? 'all';
        $all    = $this->model->all();
        $counts = [
            'all'       => count($all),
            'active'    => count(array_filter($all, fn($t)=>!$t['done'])),
            'completed' => count(array_filter($all, fn($t)=> $t['done'])),
        ];
        $tasks = match($filter) {
            'active'    => array_values(array_filter($all, fn($t)=>!$t['done'])),
            'completed' => array_values(array_filter($all, fn($t)=> $t['done'])),
            default     => $all,
        };
        render('tasks/list', [
            'tasks' => $tasks,
            'counts'=> $counts,
            'filter'=> $filter,
            'csrf'  => csrf_token(),
            'flash' => flashes(),
        ]);
    }

    public function create(): void {
        if (!csrf_ok()) redirect('/');
        $text = trim($_POST['text'] ?? '');
        if ($text === '') {
            flash('error','Task cannot be empty.');
        } elseif (mb_strlen($text) > 200) {
            flash('error','Keep tasks under 200 characters.');
        } else {
            $this->model->add($text);
            flash('success','Task added.');
        }
        redirect('/');
    }

    public function toggle(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->toggle($_POST['id'] ?? '');
        flash('info','Toggled.');
        redirect('/');
    }

    public function delete(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->delete($_POST['id'] ?? '');
        flash('success','Task deleted.');
        redirect('/');
    }

    public function update(): void {
        if (!csrf_ok()) redirect('/');
        $id = $_POST['id'] ?? '';
        $text = trim($_POST['text'] ?? '');
        if ($text === '') flash('error','Task cannot be empty.');
        else { $this->model->update($id,$text); flash('success','Task updated.'); }
        redirect('/');
    }

    public function clearCompleted(): void {
        if (!csrf_ok()) redirect('/');
        $this->model->clearCompleted();
        flash('info','Cleared completed tasks.');
        redirect('/');
    }
}
