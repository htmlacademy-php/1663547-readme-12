USE Readme;
# добавил пользователей
INSERT users(email, name, password, avatar_path)
VALUES
('larisa@gmail.com','Лариса', 123,'img/userpic-larisa-small.jpg'),
('vladik@gmail.com','Владик', 321,'img/userpic.jpg'),
('viktor@gmail.com','Виктор', 1234,'img/userpic-mark.jpg');

# добавил список типов контента для поста
INSERT type_content(name, class_name)
VALUES
('Текст', 'text'),
('Цитата', 'quote'),
('Картинка', 'photo'),
('Видео', 'video'),
('Ссылка', 'link');

# добавил хештег к каждому пользователю
INSERT hash(name)
VALUES ('@larisa'), ('@vladik'), ('@viktor');

# добавил посты с существующих массивов
INSERT post(heading, content, author_quote, image, video, link, number_views, users_id, hash_id, type_content_id)
VALUES
('Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', NULL, NULL, NULL, NULL, 12, 1, 1, 2),
('Игра престолов', 'С учётом сложившейся международной обстановки, синтетическое тестирование позволяет выполнить важные задания по разработке первоочередных требований. С другой стороны, высокотехнологичная концепция общественного уклада предопределяет высокую востребованность инновационных методов управления процессами! Как уже неоднократно упомянуто, диаграммы связей представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть описаны максимально подробно. Равным образом, постоянный количественный рост и сфера нашей активности, в своём классическом представлении, допускает внедрение поэтапного и последовательного развития общества. В своём стремлении повысить качество жизни, они забывают, что реализация намеченных плановых заданий предоставляет широкие возможности для стандартных подходов.', NULL, NULL, NULL, NULL, 10, 2, 2, 1),
('Наконец, обработал фотки!', NULL, NULL, 'img/rock-medium.jpg', NULL, NULL, 14, 3, 3, 3),
('Моя мечта', NULL, NULL, 'img/coast-medium.jpg', NULL, NULL, 31, 1, 1, 3),
('Лучшие курсы', NULL, NULL, NULL, NULL, 'http://www.htmlacademy.ru', 25, 2, 2, 5);

# добавил пару комментариев
INSERT comment( content, users_id, post_id)
VALUES
('Можно было и лучше найти.', 2, 1),
('Неплохо', 3, 1),
('Неплохо.', 3, 2),
('Хорошие работы.', 1, 3);

# получил список постов для конкретного пользователя
SELECT * FROM post WHERE users_id = 1;

# добавить лайк к посту
INSERT INTO likes SET users_id = 1, post_id = 2;

# получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT p.id, p.heading, p.content, p.number_views, p.author_quote, t.name
FROM post p
JOIN type_content t
ON p.type_content_id = t.id
ORDER BY number_views DESC;

# получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT c.id, c.content, u.email
FROM comment c
JOIN users u
ON c.users_id = u.id
WHERE post_id = 1;

# подписаться на пользователя
INSERT INTO subscrip SET users_id = 1, users_subs = 2;


