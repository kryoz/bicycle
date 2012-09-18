<?php
/* small global functions */


/**
 * number formatting
 * @param mixed $number число
 */
function numformat($number)
{
    $n = number_format(abs($number), 0, ',', ' ');
    $n = str_replace(' ', '&nbsp;', $n);
    return $n;
}
/**
 * upcase conversion for all encodings
 * @param string $str
 * @return string
 */
function mb_ucfirst($str)
{
    return mb_convert_case($str, MB_CASE_TITLE);
}

/**
 * Russian noun case conversion "в" (in)
 * @param string $name
 */
function caseIn($name)
{
    $len = strlen($name)-1;
    switch ( substr($name, -1, 1) )
    {
        case 'а' : $name[$len] = 'у'; break;
        case 'я' : $name[$len] = 'ю'; break;
    }
    
    return $name;
}

/**
 * Russian date
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

function getreferer($override)
{
    return getenv("HTTP_REFERER") ? getenv("HTTP_REFERER") : 'http://'.$_SERVER['HTTP_HOST'].URLROOT.$override;
}