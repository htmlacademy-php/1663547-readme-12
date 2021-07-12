<?php
/**
 * @var mysqli $con
 */
require_once ('helpers.php');
require_once('functions.php');
require_once('connection.php');

$is_auth = rand(0, 1);
$user_name = 'Леонид';
$title = 'Популярное';

$type_content_id = filter_input(INPUT_GET,'type-content',FILTER_VALIDATE_INT);

if (!$con){
$error = mysqli_connect_error();
$page_content = include_template('error.php',['error'=> $error]);
} else {
    $result_type = mysqli_query($con, 'SELECT * FROM type_content');
    $types = mysqli_fetch_all($result_type, MYSQLI_ASSOC);
    }
        $sql_post = 'SELECT p.id, p.heading, p.content, p.image, p.link, p.author_quote,
            p.number_views, u.avatar_path, u.name, t.class_name
            FROM post p
            JOIN users u
            ON p.users_id = u.id
            JOIN type_content t
            ON p.type_content_id = t.id';

            if ($type_content_id) {
                $sql_post .= ' WHERE p.type_content_id ='. $type_content_id;
            }
            $sql_post .= ' ORDER BY number_views DESC';
            $result_post = mysqli_query($con, $sql_post);
            $posts = mysqli_fetch_all($result_post, MYSQLI_ASSOC);
            $page_content = include_template('main.php',
                [
                'title' => $title,
                'posts' => $posts,
                'types' => $types,
                'type_content_id' => $type_content_id
                ]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'user_name'=> $user_name]);
print($layout_content);
