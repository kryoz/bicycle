<?php
/* ��������������� ���������� ������� */


/**
 * �������������� ����� � ������������ � ������� ��� ������ �� �����
 * @param mixed $number �����
 */
function numformat($number)
{
    $n = number_format(abs($number), 0, ',', ' ');
    $n = str_replace(' ', '&nbsp;', $n);
    return $n;
}
/**
 * ����� �������� � ������� ������� ������ ����� � ������ ��� ����� ������
 * @param string $str
 * @return string
 */
function mb_ucfirst($str)
{
    return mb_substr(mb_strtoupper($str, INNERCODEPAGE), 0, 1, INNERCODEPAGE) . mb_substr($str, 1, mb_strlen($str)-1, INNERCODEPAGE);
}

/**
 * ������� ���� � ������� ����������
 * @return string
 */
function rus_date() {
    $translate = array(
    "am" => "��",
    "pm" => "��",
    "AM" => "��",
    "PM" => "��",
    "Monday" => "�����������",
    "Mon" => "��",
    "Tuesday" => "�������",
    "Tue" => "��",
    "Wednesday" => "�����",
    "Wed" => "��",
    "Thursday" => "�������",
    "Thu" => "��",
    "Friday" => "�������",
    "Fri" => "��",
    "Saturday" => "�������",
    "Sat" => "��",
    "Sunday" => "�����������",
    "Sun" => "��",
    "January" => "������",
    "Jan" => "���",
    "February" => "�������",
    "Feb" => "���",
    "March" => "�����",
    "Mar" => "���",
    "April" => "������",
    "Apr" => "���",
    "May" => "���",
    "May" => "���",
    "June" => "����",
    "Jun" => "���",
    "July" => "����",
    "Jul" => "���",
    "August" => "�������",
    "Aug" => "���",
    "September" => "��������",
    "Sep" => "���",
    "October" => "�������",
    "Oct" => "���",
    "November" => "������",
    "Nov" => "���",
    "December" => "�������",
    "Dec" => "���",
    "st" => "��",
    "nd" => "��",
    "rd" => "�",
    "th" => "��"
    );
    
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
}