<?php
session_start();
require_once 'auth_check.php';
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/yaml; charset=UTF-8');
header('Content-Disposition: attachment; filename="lists_export.yaml"');

$query = "SELECT l.id AS list_id, l.title AS list_name, 
                 li.id AS item_id, li.youtube_title, li.youtube_video_id, li.added_at, 
                 u.username, u.id AS user_id
          FROM lists l
          LEFT JOIN list_items li ON l.id = li.list_id
          LEFT JOIN users u ON li.user_id = u.id
          ORDER BY l.id, li.added_at";

$result = $conn->query($query);

$lists = [];

while ($row = $result->fetch_assoc()) {
    $list_id = $row['list_id'];

    if (!isset($lists[$list_id])) {
        $lists[$list_id] = [
            'id' => $list_id,
            'title' => $row['list_name'],
            'items' => []
        ];
    }

    if ($row['item_id'] !== null) {
        $user_hash = hash('sha256', $row['user_id'] . $row['username']);

        $item = [
            'id' => $row['item_id'],
            'title' => $row['youtube_title'],
            'video_id' => $row['youtube_video_id'],
            'added_at' => $row['added_at'],
            'user_hash' => $user_hash
        ];

        $lists[$list_id]['items'][] = $item;
    }
}

// Εξαγωγή σε YAML
if (function_exists('yaml_emit')) {
    echo yaml_emit(['lists' => array_values($lists)]);
} else {
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
                $yaml .= $spaces . "$key: \"$value\"\n";
            }
        }
        return $yaml;
    }

    echo "lists:\n" . array_to_yaml(array_values($lists), 1);
}
?>
