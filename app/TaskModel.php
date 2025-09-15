<?php
namespace App;

final class TaskModel {
    /** @return array<int,array{text:string,done:bool,id:string}> */
    public function all(): array {
        $raw = json_decode(file_get_contents(STORAGE), true) ?: [];
        return $raw;
    }

    /** @param array<int,array{text:string,done:bool,id:string}> $tasks */
    private function save(array $tasks): void {
        file_put_contents(STORAGE, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
    }

    public function add(string $text): void {
        $tasks = $this->all();
        $tasks[] = ['id' => bin2hex(random_bytes(6)), 'text' => $text, 'done' => false];
        $this->save($tasks);
    }

    public function toggle(string $id): void {
        $tasks = $this->all();
        foreach ($tasks as &$t) if ($t['id'] === $id) { $t['done'] = !$t['done']; break; }
        $this->save($tasks);
    }

    public function delete(string $id): void {
        $tasks = array_values(array_filter($this->all(), fn($t) => $t['id'] !== $id));
        $this->save($tasks);
    }

    public function update(string $id, string $text): void {
        $tasks = $this->all();
        foreach ($tasks as &$t) if ($t['id'] === $id) { $t['text'] = $text; break; }
        $this->save($tasks);
    }

    public function clearCompleted(): void {
        $this->save(array_values(array_filter($this->all(), fn($t) => !$t['done'])));
    }
}
