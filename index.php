<?php
require ('helpers.php');
require_once('functions.php');

$con = mysqli_connect("localhost", "root", "root","readme");
mysqli_set_charset($con, "utf8");

$sql_type = "SELECT name, class_name FROM type_content";

    if ($result_type = mysqli_query($con, $sql_type)) {
        // получаем все категории в виде двумерного массива
        $type = mysqli_fetch_all($result_type, MYSQLI_ASSOC);
        //$type_content = include_template('main.php', ['type' => $type]);
    }
    else {
        // получить текст последней ошибки
        $error = mysqli_error($con);
        $type_content = include_template('error.php', ['error' => $error]);
    }

$sql_post = "SELECT p.id, p.heading, p.content, p.image, p.link, p.author_quote, p.number_views, u.avatar_path, u.name
                 FROM post p
                 JOIN users u
                 ON p.users_id = u.id
                 ORDER BY number_views DESC";

if ($result_post = mysqli_query($con, $sql_post)) {
    // получаем все категории в виде двумерного массива
    $post = mysqli_fetch_all($result_post, MYSQLI_ASSOC);
    //$post_content = include_template('main.php', ['post' => $post]);
}

else {
    // получить текст последней ошибки
    $error = mysqli_error($con);
    $content = include_template('error.php', ['error' => $error]);
}

$is_auth = rand(0, 1);

$user_name = 'Леонид'; // укажите здесь ваше имя
$title = 'Популярное';
$posts= [
    [
        'name' => 'Лариса',
        'avatar' => 'img/userpic-larisa-small.jpg',
        'heading' => 'Цитата',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'type' => 'post-quote'
    ],

    [
        'name' => 'Владик',
        'avatar' => 'img/userpic.jpg',
        'heading' => 'Игра престолов',
        'content' => 'С учётом сложившейся международной обстановки, синтетическое тестирование позволяет выполнить важные задания по разработке первоочередных требований. С другой стороны, высокотехнологичная концепция общественного уклада предопределяет высокую востребованность инновационных методов управления процессами! Как уже неоднократно упомянуто, диаграммы связей представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть описаны максимально подробно. Равным образом, постоянный количественный рост и сфера нашей активности, в своём классическом представлении, допускает внедрение поэтапного и последовательного развития общества. В своём стремлении повысить качество жизни, они забывают, что реализация намеченных плановых заданий предоставляет широкие возможности для стандартных подходов.',
        'type' => 'post-text'
    ],
    [
        'name' => 'Виктор',
        'avatar' => 'img/userpic-mark.jpg',
        'heading' => 'Наконец, обработал фотки!',
        'content' => 'img/rock-medium.jpg',
        'type' => 'post-photo'
    ],
    [
        'name' => 'Лариса',
        'avatar' => 'img/userpic-larisa-small.jpg',
        'heading' => 'Моя мечта',
        'content' =>'img/coast-medium.jpg',
        'type' => 'post-photo'
    ],
    [
        'name' => 'Владик',
        'avatar' => 'img/userpic.jpg',
        'heading' => 'Лучшие курсы',
        'content' => 'http://www.htmlacademy.ru',
        'type' => 'post-link'
    ]
];

// HTML-код главной страницы
$page_content = include_template('main.php', ['content'=> $posts,
                                                    'title' => $title,
                                                    'post' => $post,
                                                    'type' => $type]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content,
                                                        'user_name'=> $user_name]);

print($layout_content);





?>
