<?php
require_once('helpers.php');
require_once('connection.php');

$is_auth = rand(0, 1);
$user_name = 'Леонид';
$title = 'Популярное';

$id = 0;
$id = filter_input(INPUT_GET, 'id');

    $sql = "SELECT p.id, p.heading, p.content, p.image, p.link, u.name, u.avatar_path, t.class_name FROM post p JOIN users u ON p.users_id = u.id JOIN type_content t ON p.type_content_id = t.id WHERE p.id = $id";

    if ($result = mysqli_query($con, $sql)) {
        $active_post_col = mysqli_fetch_all($result, MYSQLI_ASSOC);
var_dump($active_post_col);
        if (!$active_post_col) {
            http_response_code(404);
            header("Location: 404.html");
        }
        $active_content_type_class = $active_post_col[0]['class_name'];
        $active_post_heading = $active_post_col[0]['heading'];
        $active_post_content = $active_post_col[0]['content'];
        $active_post_link = $active_post_col[0]['link'];
        $active_post_user_name = $active_post_col[0]['name'];
        $active_post_avatar_path = $active_post_col[0]['avatar_path'];

    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
print ($active_post_col);
$post_content = include_template("post-$active_content_type_class.php", ['content' => $active_post_content, 'post_source_link' => $active_post_link]);

$page_content = include_template('one-post.php', ['content' => $post_content, 'post' => $id, 'title' => $active_post_heading, 'active_post_user_email' => $active_post_user_name, 'active_post_avatar_path' => $active_post_avatar_path]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'modifier' => 'page__main--publication', 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

print($page_content);
