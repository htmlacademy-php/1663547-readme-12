<?php
require_once('helpers.php');
require_once('connection.php');

$is_auth = rand(0, 1);
$user_name = 'Леонид';
$title = 'Популярное';

$id = filter_input(INPUT_GET, 'post_id');

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = "SELECT p.id, p.heading, p.content, p.image, p.link, u.name, u.avatar_path, t.class_name
        FROM post p
        JOIN users u ON p.users_id = u.id
        JOIN type_content t ON p.type_content_id = t.id
        WHERE p.id = $id";
    $result = mysqli_query($con, $sql);
    $active_post = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (!$active_post){
            http_response_code(404);
            exit();
        } else {
            $error = mysqli_error($con);
            $page_content = include_template('error.php', ['error' => $error]);
        }
 }
$post_content = include_template("post-". $active_post['class_name']. ".php", ['content' => $active_post]);
$page_content = include_template('one-post.php',
    [
    'content' => $post_content,
    'post' => $active_post
    ]);
$layout_content = include_template('layout.php',
    [
    'content' => $page_content,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
    ]);
print($layout_content);
