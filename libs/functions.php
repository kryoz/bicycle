<?php
/* Вспомогательные глобальные функции */


/**
 * Преобразование числа в соответствие с локалью для вывода на экран
 * @param mixed $number число
 */
function numformat($number)
{
    $n = number_format(abs($number), 0, ',', ' ');
    $n = str_replace(' ', '&nbsp;', $n);
    return $n;
}
/**
 * Метод перевода в верхний регистр первой буквы в строке для любой локали
 * @param string $str
 * @return string
 */
function mb_ucfirst($str)
{
    return mb_substr(mb_strtoupper($str, INNERCODEPAGE), 0, 1, INNERCODEPAGE) . mb_substr($str, 1, mb_strlen($str)-1, INNERCODEPAGE);
}

/**
 * Вывести дату в русской словоформе
 * @return string
 */
function rus_date() {
    $translate = array(
    "am" => "дп",
    "pm" => "пп",
    "AM" => "ДП",
    "PM" => "ПП",
    "Monday" => "Понедельник",
    "Mon" => "Пн",
    "Tuesday" => "Вторник",
    "Tue" => "Вт",
    "Wednesday" => "Среда",
    "Wed" => "Ср",
    "Thursday" => "Четверг",
    "Thu" => "Чт",
    "Friday" => "Пятница",
    "Fri" => "Пт",
    "Saturday" => "Суббота",
    "Sat" => "Сб",
    "Sunday" => "Воскресенье",
    "Sun" => "Вс",
    "January" => "Января",
    "Jan" => "Янв",
    "February" => "Февраля",
    "Feb" => "Фев",
    "March" => "Марта",
    "Mar" => "Мар",
    "April" => "Апреля",
    "Apr" => "Апр",
    "May" => "Мая",
    "May" => "Мая",
    "June" => "Июня",
    "Jun" => "Июн",
    "July" => "Июля",
    "Jul" => "Июл",
    "August" => "Августа",
    "Aug" => "Авг",
    "September" => "Сентября",
    "Sep" => "Сен",
    "October" => "Октября",
    "Oct" => "Окт",
    "November" => "Ноября",
    "Nov" => "Ноя",
    "December" => "Декабря",
    "Dec" => "Дек",
    "st" => "ое",
    "nd" => "ое",
    "rd" => "е",
    "th" => "ое"
    );
    
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
}