<?php
session_start();
require 'config.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Ρύθμιση της κεφαλίδας για εξαγωγή YAML
header('Content-Type: text/yaml; charset=UTF-8');
header('Content-Disposition: attachment; filename="tasks_export.yaml"');

// Ανάκτηση όλων των λιστών εργασιών και των αντίστοιχων εργασιών
$query = "SELECT task_lists.id AS list_id, task_lists.title AS list_title, 
          tasks.id AS task_id, tasks.title AS task_title, tasks.status, tasks.created_at, 
          users.username AS assigned_user 
          FROM task_lists 
          LEFT JOIN tasks ON task_lists.id = tasks.list_id 
          LEFT JOIN users ON tasks.assigned_user_id = users.id 
          ORDER BY task_lists.id, tasks.created_at";

$result = $conn->query($query);

$taskLists = [];
$current_list_id = null;

while ($row = $result->fetch_assoc()) {
    $list_id = $row['list_id'];

    if (!isset($taskLists[$list_id])) {
        $taskLists[$list_id] = [
            'id' => $list_id,
            'title' => $row['list_title'],
            'tasks' => []
        ];
    }

    if ($row['task_id'] !== null) {
        $task = [
            'id' => $row['task_id'],
            'title' => $row['task_title'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'assigned_user' => $row['assigned_user'] ? $row['assigned_user'] : 'Κανένας'
        ];

        $taskLists[$list_id]['tasks'][] = $task;
    }
}

// Μετατροπή σε YAML
if (function_exists('yaml_emit')) {
    echo yaml_emit(['task_lists' => array_values($taskLists)]);
} else {
    // Χειροκίνητη μετατροπή σε YAML (βασική υλοποίηση αν δεν υπάρχει η extension yaml)
    function array_to_yaml($data, $indent = 0) {
        $yaml = '';
        $spaces = str_repeat('  ', $indent);
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $yaml .= $spaces . "- " . array_to_yaml($value, $indent + 1);
                } else {
                    $yaml .= $spaces . $key . ":\n" . array_to_yaml($value, $indent + 1);
                }
            } else {
                if (is_numeric($key)) {
                    $yaml .= $spaces . "- " . $value . "\n";
                } else {
                    $yaml .= $spaces . "$key: \"$value\"\n";
                }
            }
        }
        return $yaml;
    }

    echo "task_lists:\n" . array_to_yaml(array_values($taskLists), 1);
}
?>
