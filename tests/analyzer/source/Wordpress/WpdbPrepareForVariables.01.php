<?php

$wpdb->get_results('SELECT * FROM table WHERE id = "'.$x1.'"');

$wpdb->get_results("SELECT * FROM table WHERE id = '$x2'");

$wpdb->get_results(<<<SQL
SELECT * FROM table WHERE id = '$x3'
SQL
);

$wpdb->get_results("SELECT * FROM {$wpdb->prefix}table WHERE id = '$x'");

// $wpdb is OK
$wpdb->get_results("SELECT * FROM {$wpdb->prefix}table WHERE id = 1");

$wpdb->prepare("SELECT * FROM {$wpdb->prefix}table2 WHERE id = 1");

?>