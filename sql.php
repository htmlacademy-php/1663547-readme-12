<?php
function getContent(array $sql){
    $sql = [ ];
    $id = filter_input(INPUT_GET, 'id');
    $sql = "SELECT p.id, p.heading, p.content, p.image, p.link, u.name, u.avatar_path, t.class_name
        FROM post p
        JOIN users u ON p.users_id = u.id
        JOIN type_content t ON p.type_content_id = t.id
        WHERE p.id = $id";
}
return $sql;


