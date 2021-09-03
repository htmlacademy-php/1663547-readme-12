<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return false;
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    return 200 === $err_flag;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if (\array_key_exists('host', $parts) && $parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Обрезает текст если количество символов больше чем заданное значение
 *
 * @param $text string Текст который нужно обработать
 * @param $number_char int Количество символов по умолчанию 300
 *
 * @return html вернет обработанные текст
 */
function cropText(string $text, int $number_char = 300)
{
    //разобьем текст на отдельные слова
    $split_text = explode(" ", $text);

    $word_length = 0;

    $reduction = false;
    $short_text[] = "";
    //считаем длину каждого слова
    foreach ($split_text as $word) {
        $word_length += mb_strlen($word, 'utf8') + 1;//использую mb_strlen т.к strlen выдает в 2 раза больше символов.
        if ($word_length >= $number_char) {
            $reduction = true;
            break;
        }
        $short_text[] = $word;
    };
    //обратно в текст
    $text = implode(" ", $short_text);

    if ($reduction != false) {
        return "<p>" . $text . "..." . "</p>" . '<a class="post-text__more-link" href="post.php?post_id=2">Читать далее</a>';
    } else {
        return "<p>" . $text . "</p>";
    }
}

/**
 * Вычисление относительного формата
 *
 * @param $timeStamp int Количество секунд
 *
 * @return string, вернет количество лет
 * @return string, вернет количество месяцев
 * @return string, вернет количество недель
 * @return string, вернет количество дней
 * @return string, вернет количество минут
 */
function get_time_ago($timeStamp)
{
    if ($timeStamp >= 60 * 60 * 24 * 365) {
        $datePast = date("Y", $timeStamp);
        return $datePast . ' ' . get_noun_plural_form($datePast, 'год', 'года', 'лет');
    } elseif ($timeStamp >= 60 * 60 * 24 * 30) {
        $datePast = date("n", $timeStamp);
        return $datePast . ' ' . get_noun_plural_form($datePast, 'месяц', 'месяца', 'месяцев');
    } elseif ($timeStamp >= 60 * 60 * 24 * 7) {
        $datePast = date("N", $timeStamp);
        return $datePast . ' ' . get_noun_plural_form($datePast, 'неделя', 'недели', 'недель');
    } elseif ($timeStamp >= 60 * 60 * 24) {
        $datePast = date("j", $timeStamp);
        return $datePast . ' ' . get_noun_plural_form($datePast, 'день', 'дня', 'дней');
    } elseif ($timeStamp >= 60 * 60) {
        $datePast = $timeStamp / 3600;
        return $datePast . ' ' . get_noun_plural_form($datePast, 'час', 'часа', 'часов');
    } elseif ($timeStamp >= 60) {
        $datePast = $timeStamp / 60;
        return $datePast . ' ' . get_noun_plural_form($datePast, 'минута', 'минуты', 'минут');
    }
}

/**
 * Функция выполняет запрос SELECT и возвращает из базы готовый массив с запрошенными данными
 * @param mysqli $con подключение к базе данных
 * @param string $sql строка запроса на выборку данных
 * @param boolean $one параметр определяет возвращаемый результат двумерный массив или просто массив с данными,
 * по умолчанию false
 * @return array готовый массив с данными
 */
function make_select_query($con, $sql, $one = false)
{
    $result = mysqli_query($con, $sql);
    if ($result) {
        if ($one) {
            return mysqli_fetch_assoc($result);
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    print("Ошибка запроса: " . mysqli_error($con));
}

/**
 * Функция проверяет заполнено ли поле формы
 * @param string $value содержимое поля формы
 * @param string $title название поля формы
 * @return string сообщение об ошибке или null если поле заполнено
 */
function validateFilled($value, $title)
{
    if (empty($value)) {
        return $title . ". Это поле должно быть заполнено.";
    }
    return null;
}

/**
 * Функция проверяет длину текстового поля на превышение максимального значения
 * @param string $value содержимое поля формы
 * @param int $max максимально допустимое значение длины поля
 * @param string $title название поля формы
 * @return string сообщение об ошибке или null если ошибок нет
 */
function validateFilledLength($value, $max, $title)
{
    $notEmpty = validateFilled($value, $title);
    if (!$notEmpty) {
        $length = mb_strlen($value);
        if ($length > $max) {
            return "Текст не должен превышать " . $max . " знаков.";
        }
        return null;
    }
    return $notEmpty;
}

/**
 * Функция проверяет правильность ссылки
 * @param string $value содержимое поля формы
 * @param string $title название поля формы
 * @param boolean $video указатель на ссылку с Youtube, по умолчанию false
 * @return string сообщение об ошибке или null если ошибок нет
 */
function validateUrl($value, $title, $video = false)
{
    $notEmpty = validateFilled($value, $title);

    if (!$notEmpty) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return "Введен некорректный URL.";
        } elseif ($video && !check_youtube_url($value)) {
            return "Видео не существует!";
        }

        return null;
    }

    return $notEmpty;
}

function validateUrlVideo()
{

}

/**
 * Функция возвращает тип файла
 * @param string $file файл у которого проверяется тип
 * @return string тип файла
 */

function getFileType($file)
{
    return image_type_to_mime_type(exif_imagetype($file));
}

/**
 * Функция проверки соответствия типов файла
 * @param string $file_type тип файла
 * @return string возвращает ошибку если тип файла не соответствует допустимым типам, иначе возвращает null
 */
function validateFileType($file_type)
{
    $available_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($file_type, $available_types)) {
        return null;
    }

    return "Неверный формат файла. Файл может быть PNG, JPEG или GIF.";
}

/**
 * Функция заполнения полей поста для запроса
 * @param array $post входящий массив с данными поста
 * @param array $fields список полей базы данных постов
 * @return array заполненный массив поста
 */
function fillArray($post, $fields)
{
    $result = [];

    foreach ($fields as $key => $field) {
        if (isset($post[$field])) {
            $result[$field] = $post[$field];
        } else {
            $result[$field] = null;
        }
    }

    return $result;
}

/**
 * Функция возвращает значение поля формы после отправки
 * @param string $name имя поля формы
 * @return string значение поля формы
 */
function getPostVal($name)
{
    return filter_input(INPUT_POST, $name);
}



