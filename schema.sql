CREATE DATABASE Readme
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE Readme
-- Структура таблицы `users`
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       email VARCHAR(128) NOT NULL UNIQUE,
                       name CHAR(255) NOT NULL,
                       password CHAR(64) NOT NULL,
                       avatar_path CHAR(255)
);
-- Структура таблицы `comment`
CREATE TABLE `comment` (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           create_date datetime NOT NULL,
                           content text NOT NULL,
                           users_id int(11) NOT NULL,
                           post_id int(11) NOT NULL,
                           FOREIGN KEY (users_id)  REFERENCES users (id),
                           FOREIGN KEY (post_id)  REFERENCES post (id)
);
-- Структура таблицы `hash`
CREATE TABLE `hash` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name char(64) NOT NULL
);
-- Структура таблицы `like`
CREATE TABLE `like` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        users_id int(11) NOT NULL,
                        post_id int(11) NOT NULL,
                        FOREIGN KEY (users_id)  REFERENCES users (id),
                        FOREIGN KEY (post_id)  REFERENCES post (id)
);
-- Структура таблицы `message`
CREATE TABLE `message` (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           create_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                           content text NOT NULL,
                           users_id int(11) NOT NULL,
                           recipient_id int(11) NOT NULL,
                           FOREIGN KEY (users_id)  REFERENCES users (id),
                           FOREIGN KEY (recipient_id)  REFERENCES users (id)
);
-- Структура таблицы `post`
CREATE TABLE `post` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        create_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        heading char(64) NOT NULL,
                        content text NOT NULL,
                        author_quote char(64) DEFAULT NULL,
                        image char(255) DEFAULT NULL,
                        video char(255) DEFAULT NULL,
                        link char(255) DEFAULT NULL,
                        number_views int(11) NOT NULL,
                        users_id int(11) NOT NULL,
                        hash_id int(11) NOT NULL,
                        type_content_id int(11) NOT NULL,
                        FOREIGN KEY (users_id)  REFERENCES users (id),
                        FOREIGN KEY (hash_id)  REFERENCES hash (id),
                        FOREIGN KEY (type_content_id)  REFERENCES type_content (id)
);
-- Структура таблицы `subscrip`
CREATE TABLE `subscrip` (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            users_id int(11) NOT NULL,
                            users_subs int(11) NOT NULL,
                            FOREIGN KEY (users_id)  REFERENCES users (id),
                            FOREIGN KEY (users_subs)  REFERENCES users (id)
);
-- Структура таблицы `type_content`
CREATE TABLE `type_content` (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                name text NOT NULL,
                                class_name text NOT NULL
);

