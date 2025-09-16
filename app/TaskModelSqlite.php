<?php
namespace App;

use PDO;

final class TaskModelSqlite {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    /** @return array<int,array{id:string,text:string,done:int,created_at:string}> */
    public function all(): array {
        return $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $text): void {
        $id = bin2hex(random_bytes(6));
        $stmt = $this->db->prepare("INSERT INTO tasks(id,text,done) VALUES(:id,:text,0)");
        $stmt->execute([':id'=>$id, ':text'=>$text]);
    }

    public function toggle(string $id): void {
        $this->db->prepare("UPDATE tasks SET done = 1 - done WHERE id = :id")->execute([':id'=>$id]);
    }

    public function delete(string $id): void {
        $this->db->prepare("DELETE FROM tasks WHERE id = :id")->execute([':id'=>$id]);
    }

    public function update(string $id, string $text): void {
        $this->db->prepare("UPDATE tasks SET text = :t WHERE id = :id")->execute([':t'=>$text, ':id'=>$id]);
    }

    public function clearCompleted(): void {
        $this->db->exec("DELETE FROM tasks WHERE done = 1");
    }
}
