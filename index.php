<?php
require ('helpers.php');
require_once('functions.php');

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
$page_content = include_template('main.php', ['content'=> $posts, 'title' => $title]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content,
                                                        'user_name'=> $user_name]);

print($layout_content);





?>
