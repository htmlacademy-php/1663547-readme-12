<?php
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
    if ($timeStamp >= 60 * 60 * 24 * 365)
    {
        $datePast = date("Y", $timeStamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'год', 'года', 'лет');
    }
    elseif ( $timeStamp >= 60 * 60 * 24 * 30)
    {
        $datePast = date("n", $timeStamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'месяц', 'месяца', 'месяцев');
    }
    elseif ($timeStamp >= 60 * 60 * 24 * 7)
    {
        $datePast = date("N", $timeStamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'неделя', 'недели', 'недель');
    }
    elseif ($timeStamp >= 60 * 60 * 24)
    {
        $datePast = date("j", $timeStamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'день', 'дня', 'дней');
    }
    elseif ($timeStamp >= 60 * 60)
    {
        $datePast = $timeStamp/3600;
        return $datePast. ' ' .get_noun_plural_form($datePast, 'час', 'часа', 'часов');
    }
    elseif ($timeStamp >= 60 )
    {
        $datePast = $timeStamp/60;
        return $datePast. ' ' .get_noun_plural_form($datePast, 'минута', 'минуты', 'минут');
    }
}
/**
 * Функция выполняет запрос SELECT и возвращает из базы готовый массив с запрошенными данными
 * @param mysqli $db_link подключение к базе данных
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
        } elseif (!$video && !check_youtube_url($value)) {
            return "Видео не существует!";
        }
        return null;
    }
    return $notEmpty;
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
 * @param array $available_types массив с допустимыми типами файлов
 * @return string возвращает ошибку если тип файла не соответствует допустимым типам, иначе возвращает null
 */
function validateFileType($file_type, $available_types)
{
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
function getPostVal ($name) {
    return filter_input(INPUT_POST, $name);
}

