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
    return "<p>" . $text . "..." . "</p>" . '<a class="post-text__more-link" "href="#">Читать далее</a>';
  } else {
    return "<p>" . $text . "</p>";
  }
}
/**
 * Вычисление относительного формата
 *
 * @param $timeStamp int Количество секунд
 *
 * @return 1 вернет количество лет
 * @return 2 вернет количество месяцев
 * @return 3 вернет количество недель
 * @return 4 вернет количество дней
 * @return 5 вернет количество минут
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

        $datePast = date("G", $timeStamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'час', 'часа', 'часов');
    }
    elseif ($timeStamp >= 60 )
    {

        $datePast = $timeStamp/60;
        return $datePast. ' ' .get_noun_plural_form($datePast, 'минута', 'минуты', 'минут');
    }
}
?>
