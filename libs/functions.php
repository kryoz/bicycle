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
    $parts = explode(' ',$name);
    
    foreach ($parts as &$part)
    {
        if (in_array(strtolower($part), array('о-в', 'о-ва')))
            return $name;
        
        $len = strlen($part)-1;
        $ending = substr($part, -1, 1);
        $preending = substr($part, -2, 1);
        
        if ( $ending == 'а' && $preending != 'у')
            $part[$len] = 'у';
        elseif ( $ending == 'я')
        {
            if ( $preending == 'а' )
            {
                $part[$len-1] = 'у';
                $part[$len] = 'ю';
            }
            else if ( $preending == 'и' )
            {
                $part[$len] = 'ю';
            }
        }
           
    }
    
    return implode(' ', $parts);
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

/**
 * Gets referer url from HTTP headers or generates URL specified component in $override var
 * @param string $override
 * @return type
 */
function getreferer($override)
{
    return getenv("HTTP_REFERER") ? getenv("HTTP_REFERER") : 'http://'.$_SERVER['HTTP_HOST'].URLROOT.$override;
}

/**
 * Function filters comlex array from empty values
 * @param array $array
 * @return array
 */
function truncArray($array)
{
    if (is_array($array))
    {
        foreach ($array as $i=>&$item)
        {
            if (is_array($item[0]))
                $item = truncArray($item);
            else
            {
                if (!empty($item))
                {
                    $empty_elements = array_keys($item,"");

                    foreach ($empty_elements as $e)
                        unset($item[$e]);
                    
                    if (empty($item))
                        unset($array[$i]);
                }
                else
                {
                    unset($array[$i]);
                }
            }
        }
    }

    return $array;
}