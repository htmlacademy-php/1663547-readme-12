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

function get_time_ago($time_stamp)
{

    if ($time_stamp >= 60 * 60 * 24 * 365.242199)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 365.242199 days/year
         * This means that the time difference is 1 year or more
         */
        $datePast = date("Y", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'год', 'года', 'лет');

    }
    elseif ( $time_stamp >= 60 * 60 * 24 * 30.4368499)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 30.4368499 days/month
         * This means that the time difference is 1 month or more
         */
        $datePast = date("n", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'месяц', 'месяца', 'месяцев');
    }
    elseif ($time_stamp >= 60 * 60 * 24 * 7)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 7 days/week
         * This means that the time difference is 1 week or more
         */
        $datePast = date("N", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'неделя', 'недели', 'недель');
    }
    elseif ($time_stamp >= 60 * 60 * 24)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day
         * This means that the time difference is 1 day or more
         */
        $datePast = date("d", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'день', 'дня', 'дней');
    }
    elseif ($time_stamp >= 60 * 60)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour
         * This means that the time difference is 1 hour or more
         */
        $datePast = date("G", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'час', 'часа', 'часов');
    }
    elseif ($time_stamp >= 60 )
    {
        /*
         * 60 seconds/minute
         * This means that the time difference is a matter of minutes
         */
        $datePast = date("i", $time_stamp);
        return $datePast. ' ' .get_noun_plural_form($datePast, 'минута', 'минуты', 'минут');
    }
}
?>
