<?php

require_once('helpers.php');
require_once('connection.php');



$is_auth = 1;
$user_name = 'Леонид';
$add_form = true;
$users_id = 3;
$hash_id = 3;

$id = 1;
$errors = [];
$data = [];

$post_types = make_select_query($con, 'SELECT * FROM type_content');


$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    $required_fields = ['heading', 'text', 'author', 'link', 'video'];

    $rules = [
        'heading' => function ($value) {
            return validateFilled($value, 'Заголовок');
        },
        'text' => function ($value) {
            return validateFilled($value, 'Текст');
        },
        'quote' => function ($value) {
            return validateFilledLength($value, 70, "Цитата");
        },
        'author' => function ($value) {
            return validateFilled($value, 'Автор');
        },
        'link' => function ($value) {
            return validateUrl($value, 'Ссылка');
        },
        'url' => function ($value) {
            return validateUrl($value, 'Ссылка image');
        },
        'video' => function ($value) {
            return validateUrl($value, 'Ссылка Youtube', true);
        }
    ];

    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    if ($id === '1') {
        if (!empty($_FILES['photo']['name'])) {
            $tmp_name = $_FILES['photo']['tmp_name'];
            $img_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];

            $valid_type = validateFileType($file_type);
            if (null === $valid_type) {
                move_uploaded_file($tmp_name, __DIR__ . '/uploads/' . $img_name);
                $data['image'] = $img_name;
            } else {
                $errors['file'] = $valid_type;
            }
        } elseif (!empty($_POST['url'])) {
            $file_type = getFileType($_POST['url']);
            $file = file_get_contents($_POST['url']);
            if ($file_type) {
                $valid_type = validateFileType($file_type);
                if (null === $valid_type) {
                    $img_name = pathinfo($_POST['url'], PATHINFO_BASENAME);
                    file_put_contents(__DIR__ . '/uploads/' . $img_name, $file);
                    $data['image'] = $img_name;
                } else {
                    $errors['file'] = $valid_type;
                }
            }
        } else {
            $errors['url'] = "Добавьте файл или введите ссылку.";
        }
    }

    $errors = array_filter($errors);

    if (!count($errors)) {

        if (isset($data['text'])) {
            $data['content'] = $data['text'];
            $data['text'] = null;
        }
        if (isset($data['quote'])) {
            $data['content'] = $data['quote'];
            $data['quote'] = null;
        }

        $data = array_filter($data);

        $data = fillArray($data, ['heading', 'content', 'author_quote', 'image', 'video', 'link']);
        $data['users_id'] = $users_id;
        $data['hash_id'] = $hash_id;
        $data['type_content_id'] = $id;

        $sql = 'INSERT INTO post (heading, content, author_quote, image, video, link, users_id, hash_id, type_content_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql, $data);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $post_id = mysqli_insert_id($con);

            header("Location: post.php?post_id=" . $post_id);
            die();
        } else {
            print("Ошибка запроса: " . mysqli_error($con));

        }
    }

}
$add_file = "add-" . $post_types[$id - 1]['class_name'] . ".php";

$add_content = include_template($add_file, [
    'errors' => $errors
]);
$page_content = include_template('adding-post.php', [
    'content' => $add_content,
    'post_types' => $post_types,
    'errors' => $errors,
    'id' => $id
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Популярное',
    'add_form' => $add_form
]);

print($layout_content);
