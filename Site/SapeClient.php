<?php

namespace Core;

use Core\ServiceLocator\IService;
use Core\ServiceLocator\Locator;

abstract class SapeBase
{

    protected $_version = '1.1.8';
    protected $_user_agent = 'SAPE_Client PHP';
    protected $_verbose = false;
    protected $_charset = 'utf-8';
    protected $_sape_charset = '';
    protected $_server_list = array('dispenser-01.sape.ru', 'dispenser-02.sape.ru');
    protected $_cache_lifetime = 3600; // Кеширование данных на стороне сайта
    protected $_cache_reloadtime = 300; // Если скачать базу ссылок не удалось, то следующая попытка будет через столько секунд
    protected $_error = '';
    protected $_host = '';
    protected $_request_uri = '';
    protected $_multi_site = false;
    protected $_fetch_remote_type = 'curl'; // Способ подключения к удалённому серверу [file_get_contents|curl|socket]
    protected $_socket_timeout = 1; // Сколько ждать ответа
    protected $_force_show_code = false;
    protected $_is_our_bot = false; // Если наш робот
    protected $_debug = false;
    protected $_ignore_case = false; // Регистронезависимый режим работы, использовать только на свой страх и риск
    protected $_db_file = ''; // Путь к файлу с данными
    protected $_use_server_array = false; // Откуда будем брать uri страницы: $_SERVER['REQUEST_URI'] или getenv('REQUEST_URI')
    protected $_force_update_db = false;
    protected $_is_block_css_showed = false; // Флаг для отрисовки css в блочных ссылках
    protected $_is_block_ins_beforeall_showed = false;

    protected function __construct($options = null)
    {
        $host = '';

        if (is_array($options)) {
            if (isset($options['host'])) {
                $host = $options['host'];
            }
        } elseif (strlen($options)) {
            $host = $options;
            $options = array();
        } else {
            $options = array();
        }

        if (isset($options['use_server_array']) && $options['use_server_array'] == true) {
            $this->_use_server_array = true;
        }

        // Какой сайт?
        if (strlen($host)) {
            $this->_host = $host;
        } else {
            $this->_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'phpunit';
        }

        $this->_host = preg_replace('/^http:\/\//', '', $this->_host);
        $this->_host = preg_replace('/^www\./', '', $this->_host);

        // Какая страница?
        if (isset($options['request_uri']) && strlen($options['request_uri'])) {
            $this->_request_uri = $options['request_uri'];
        } elseif ($this->_use_server_array === false) {
            $this->_request_uri = getenv('REQUEST_URI');
        }

        if (strlen($this->_request_uri) == 0) {
            $this->_request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        }

        // На случай, если хочется много сайтов в одной папке
        if (isset($options['multi_site']) && $options['multi_site'] == true) {
            $this->_multi_site = true;
        }

        // Выводить информацию о дебаге
        if (isset($options['debug']) && $options['debug'] == true) {
            $this->_debug = true;
        }

        // Определяем наш ли робот
        if (isset($_COOKIE['sape_cookie']) && ($_COOKIE['sape_cookie'] == _SAPE_USER)) {
            $this->_is_our_bot = true;
            if (isset($_COOKIE['sape_debug']) && ($_COOKIE['sape_debug'] == 1)) {
                $this->_debug = true;
                //для удобства дебега саппортом
                $this->_options = $options;
                $this->_server_request_uri = $this->_request_uri = $_SERVER['REQUEST_URI'];
                $this->_getenv_request_uri = getenv('REQUEST_URI');
                $this->_SAPE_USER = _SAPE_USER;
            }
            if (isset($_COOKIE['sape_updatedb']) && ($_COOKIE['sape_updatedb'] == 1)) {
                $this->_force_update_db = true;
            }
        } else {
            $this->_is_our_bot = false;
        }

        // Сообщать об ошибках
        if (isset($options['verbose']) && $options['verbose'] == true || $this->_debug) {
            $this->_verbose = true;
        }

        // Кодировка
        if (isset($options['charset']) && strlen($options['charset'])) {
            $this->_charset = $options['charset'];
        }

        if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type'])) {
            $this->_fetch_remote_type = $options['fetch_remote_type'];
        }

        if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
            $this->_socket_timeout = $options['socket_timeout'];
        }

        // Всегда выводить чек-код
        if (isset($options['force_show_code']) && $options['force_show_code'] == true) {
            $this->_force_show_code = true;
        }

        if (!defined('_SAPE_USER')) {
            return $this->raiseError('Не задана константа _SAPE_USER');
        }

        //Не обращаем внимания на регистр ссылок
        if (isset($options['ignore_case']) && $options['ignore_case'] == true) {
            $this->_ignore_case = true;
            $this->_request_uri = strtolower($this->_request_uri);
        }
    }

    /**
     * Функция обработки ошибок
     */
    protected function raiseError($e)
    {

        $this->_error = '<p style="color: red; font-weight: bold;">SAPE ERROR: ' . $e . '</p>';

        if ($this->_verbose == true) {
            print $this->_error;
        }

        return false;
    }

    /**
     * Загрузка данных
     */
    protected function loadData()
    {
        $this->_db_file = 'sape_links';


        $data = $this->read($this->_db_file);

        if (!$data) {

            $path = $this->getDispenserPath();
            if (strlen($this->_charset)) {
                $path .= '&charset=' . $this->_charset;
            }

            foreach ($this->_server_list as $i => $server) {
                if ($data = $this->fetch_remote_file($server, $path)) {
                    if (substr($data, 0, 12) == 'FATAL ERROR:') {
                        $this->raiseError($data);
                    } else {
                        $data = @unserialize($data);
                        $ttl = $this->_cache_lifetime;
                        break;
                    }
                }
            }

            if (!$data) {
                $ttl = $this->_cache_reloadtime;
            }

            $data['__sape_charset__'] = $this->_charset;
            $data['__last_update__'] = time();
            $data['__multi_site__'] = $this->_multi_site;
            $data['__fetch_remote_type__'] = $this->_fetch_remote_type;
            $data['__ignore_case__'] = $this->_ignore_case;
            $data['__php_version__'] = phpversion();
            $data['__server_software__'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';

            $this->write($this->_db_file, $data, $ttl);
        }

        // Убиваем PHPSESSID
        if (strlen(session_id())) {
            $session = session_name() . '=' . session_id();
            $this->_request_uri = str_replace(array('?' . $session, '&' . $session), '', $this->_request_uri);
        }

        $this->setData($data);
    }

    protected function read($filename)
    {
        $cache = Locator::get('CACHE');
        return $cache->get(CP . $filename);
    }

    protected function write($filename, $data, $ttl)
    {
        $cache = Locator::get('CACHE');

        return $cache->set(CP . $filename, $data, $ttl);
    }

    protected function fetch_remote_file($host, $path)
    {

        $user_agent = $this->_user_agent . ' ' . $this->_version;

        @ini_set('allow_url_fopen', 1);
        @ini_set('default_socket_timeout', $this->_socket_timeout);
        @ini_set('user_agent', $user_agent);
        if (
            $this->_fetch_remote_type == 'file_get_contents' ||
            (
                $this->_fetch_remote_type == '' &&
                function_exists('file_get_contents') &&
                ini_get('allow_url_fopen') == 1
            )
        ) {
            $this->_fetch_remote_type = 'file_get_contents';
            if ($data = @file_get_contents('http://' . $host . $path)) {
                return $data;
            }
        } elseif (
            $this->_fetch_remote_type == 'curl' ||
            (
                $this->_fetch_remote_type == '' &&
                function_exists('curl_init')
            )
        ) {
            $this->_fetch_remote_type = 'curl';
            if ($ch = curl_init()) {

                curl_setopt($ch, CURLOPT_URL, 'http://' . $host . $path);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_socket_timeout);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

                $data = curl_exec($ch);
                curl_close($ch);

                if ($data) {
                    return $data;
                }
            }
        } else {
            $this->_fetch_remote_type = 'socket';
            $buff = '';
            $fp = @fsockopen($host, 80, $errno, $errstr, $this->_socket_timeout);
            if ($fp) {
                @fputs($fp, "GET {$path} HTTP/1.1\r\nHost: {$host}\r\n");
                @fputs($fp, "User-Agent: {$user_agent}\r\n\r\n");
                while (!@feof($fp)) {
                    $buff .= @fgets($fp, 128);
                }
                @fclose($fp);

                $page = explode("\r\n\r\n", $buff);

                return $page[1];
            }
        }

        return $this->raiseError('Не могу подключиться к серверу: ' . $host . $path . ', type: ' . $this->_fetch_remote_type);
    }

    abstract protected function setData($data);
    abstract protected function getDispenserPath();
}

/**
 * Класс для работы с обычными ссылками
 */
class SapeClient extends SapeBase implements IService
{

    private static $instance;
    protected $_links_delimiter = '';
    protected $_links = array();
    protected $_links_page = array();

    protected function __construct($options = null)
    {
        parent::__construct($options);
        $this->loadData();
    }

    /**
     *
     * @return SapeClient
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getServiceName()
    {
        return 'SAPE';
    }

    public function return_links($n = null, $offset = 0, $options = null)
    {
        return $this->returnLinks($n, $offset, $options);
    }

    /**
     * Вывод ссылок в обычном виде - текст с разделителем
     *
     * @param int $n Количествово
     * @param int $offset Смещение
     * @param array $options Опции
     *
     * <code>
     * $options = array();
     * $options['as_block'] = (false|true);
     * // Показывать ли ссылки в виде блока
     * </code>
     *
     * @see return_block_links(returnBlockLinks
     * @return string
     */
    public function returnLinks($n = null, $offset = 0, $options = null)
    {

        //Опрелелить, как выводить ссылки
        $as_block = $this->_show_only_block;

        if (is_array($options) && isset($options['as_block']) && false == $as_block) {
            $as_block = $options['as_block'];
        }

        if (true == $as_block && isset($this->_block_tpl)) {
            return $this->returnBlockLinks($n, $offset, $options);
        }

        //-------

        if (is_array($this->_links_page)) {

            $total_page_links = count($this->_links_page);

            if (!is_numeric($n) || $n > $total_page_links) {
                $n = $total_page_links;
            }

            $links = array();

            for ($i = 1; $i <= $n; $i++) {
                if ($offset > 0 && $i <= $offset) {
                    array_shift($this->_links_page);
                } else {
                    $links[] = array_shift($this->_links_page);
                }
            }

            $html = join($this->_links_delimiter, $links);

            // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
            if (
                strlen($this->_charset) > 0 &&
                strlen($this->_sape_charset) > 0 &&
                $this->_sape_charset != $this->_charset &&
                function_exists('iconv')
            ) {
                $new_html = @iconv($this->_sape_charset, $this->_charset, $html);
                if ($new_html) {
                    $html = $new_html;
                }
            }

            if ($this->_is_our_bot) {
                $html = '<sape_noindex>' . $html . '</sape_noindex>';
            }
        } else {
            $html = $this->_links_page;
            if ($this->_is_our_bot) {
                $html .= '<sape_noindex></sape_noindex>';
            }
        }

        if ($this->_debug) {
            $html .= print_r($this, true);
        }

        return $html;
    }

    /**
     * Вывод ссылок в виде блока
     *
     * @param int $n Количествово
     * @param int $offset Смещение
     * @param array $options Опции
     *
     * <code>
     * $options = array();
     * $options['block_no_css'] = (false|true);
     * // Переопределяет запрет на вывод css в коде страницы: false - выводить css
     * $options['block_orientation'] = (1|0);
     * // Переопределяет ориентацию блока: 1 - горизонтальная, 0 - вертикальная
     * $options['block_width'] = ('auto'|'[?]px'|'[?]%'|'[?]');
     * // Переопределяет ширину блока:
     * // 'auto'  - определяется шириной блока-предка с фиксированной шириной,
     * // если такового нет, то займет всю ширину
     * // '[?]px' - значение в пикселях
     * // '[?]%'  - значение в процентах от ширины блока-предка с фиксированной шириной
     * // '[?]'   - любое другое значение, которое поддерживается спецификацией CSS
     * </code>
     *
     * @return string
     */
    public function returnBlockLinks($n = null, $offset = 0, $options = null)
    {

        // Объединить параметры
        if (empty($options)) {
            $options = array();
        }

        $defaults = array();
        $defaults['block_no_css'] = false;
        $defaults['block_orientation'] = 1;
        $defaults['block_width'] = '';

        $ext_options = array();
        if (isset($this->_block_tpl_options) && is_array($this->_block_tpl_options)) {
            $ext_options = $this->_block_tpl_options;
        }

        $options = array_merge($defaults, $ext_options, $options);

        // Ссылки переданы не массивом (чек-код) => выводим как есть + инфо о блоке
        if (!is_array($this->_links_page)) {
            $html = $this->returnArrayLinksHtml('', array('is_block_links' => true));
            return $this->returnHtml($this->_links_page . $html);
        } // Не переданы шаблоны => нельзя вывести блоком - ничего не делать
        elseif (!isset($this->_block_tpl)) {
            return $this->returnHtml('');
        }

        // Определим нужное число элементов в блоке

        $total_page_links = count($this->_links_page);

        $need_show_obligatory_block = false;
        $need_show_conditional_block = false;
        $n_requested = 0;

        if (isset($this->_block_ins_itemobligatory)) {
            $need_show_obligatory_block = true;
        }

        if (is_numeric($n) && $n >= $total_page_links) {

            $n_requested = $n;

            if (isset($this->_block_ins_itemconditional)) {
                $need_show_conditional_block = true;
            }
        }

        if (!is_numeric($n) || $n > $total_page_links) {
            $n = $total_page_links;
        }

        // Выборка ссылок
        $links = array();
        for ($i = 1; $i <= $n; $i++) {
            if ($offset > 0 && $i <= $offset) {
                array_shift($this->_links_page);
            } else {
                $links[] = array_shift($this->_links_page);
            }
        }

        $html = '';

        // Подсчет числа опциональных блоков
        $nof_conditional = 0;
        if (count($links) < $n_requested && true == $need_show_conditional_block) {
            $nof_conditional = $n_requested - count($links);
        }

        //Если нет ссылок и нет вставных блоков, то ничего не выводим
        if (empty($links) && $need_show_obligatory_block == false && $nof_conditional == 0) {

            $return_links_options = array(
                'is_block_links' => true,
                'nof_links_requested' => $n_requested,
                'nof_links_displayed' => 0,
                'nof_obligatory' => 0,
                'nof_conditional' => 0
            );

            $html = $this->returnArrayLinksHtml($html, $return_links_options);

            return $this->returnHtml($html);
        }

        // Делаем вывод стилей, только один раз. Или не выводим их вообще, если так задано в параметрах
        if (!$this->_is_block_css_showed && false == $options['block_no_css']) {
            $html .= $this->_block_tpl['css'];
            $this->_is_block_css_showed = true;
        }

        // Вставной блок в начале всех блоков
        if (isset($this->_block_ins_beforeall) && !$this->_is_block_ins_beforeall_showed) {
            $html .= $this->_block_ins_beforeall;
            $this->_is_block_ins_beforeall_showed = true;
        }

        // Вставной блок в начале блока
        if (isset($this->_block_ins_beforeblock)) {
            $html .= $this->_block_ins_beforeblock;
        }

        // Получаем шаблоны в зависимости от ориентации блока
        $block_tpl_parts = $this->_block_tpl[$options['block_orientation']];

        $block_tpl = $block_tpl_parts['block'];
        $item_tpl = $block_tpl_parts['item'];
        $item_container_tpl = $block_tpl_parts['item_container'];
        $item_tpl_full = str_replace('{item}', $item_tpl, $item_container_tpl);
        $items = '';

        $nof_items_total = count($links);
        foreach ($links as $link) {

            preg_match('#<a href="(https?://([^"/]+)[^"]*)"[^>]*>[\s]*([^<]+)</a>#i', $link, $link_item);

            if (function_exists('mb_strtoupper') && strlen($this->_sape_charset) > 0) {
                $header_rest = mb_substr($link_item[3], 1, mb_strlen($link_item[3], $this->_sape_charset) - 1, $this->_sape_charset);
                $header_first_letter = mb_strtoupper(mb_substr($link_item[3], 0, 1, $this->_sape_charset), $this->_sape_charset);
                $link_item[3] = $header_first_letter . $header_rest;
            } elseif (function_exists('ucfirst') && (strlen($this->_sape_charset) == 0 || strpos($this->_sape_charset, '1251') !== false)) {
                $link_item[3][0] = ucfirst($link_item[3][0]);
            }

            // Если есть раскодированный URL, то заменить его при выводе

            if (isset($this->_block_uri_idna) && isset($this->_block_uri_idna[$link_item[2]])) {
                $link_item[2] = $this->_block_uri_idna[$link_item[2]];
            }

            $item = $item_tpl_full;
            $item = str_replace('{header}', $link_item[3], $item);
            $item = str_replace('{text}', trim($link), $item);
            $item = str_replace('{url}', $link_item[2], $item);
            $item = str_replace('{link}', $link_item[1], $item);
            $items .= $item;
        }

        // Вставной обязатльный элемент в блоке
        if (true == $need_show_obligatory_block) {
            $items .= str_replace('{item}', $this->_block_ins_itemobligatory, $item_container_tpl);
            $nof_items_total += 1;
        }

        // Вставные опциональные элементы в блоке
        if ($need_show_conditional_block == true && $nof_conditional > 0) {
            for ($i = 0; $i < $nof_conditional; $i++) {
                $items .= str_replace('{item}', $this->_block_ins_itemconditional, $item_container_tpl);
            }
            $nof_items_total += $nof_conditional;
        }

        if ($items != '') {
            $html .= str_replace('{items}', $items, $block_tpl);

            // Проставляем ширину, чтобы везде одинковая была
            if ($nof_items_total > 0) {
                $html = str_replace('{td_width}', round(100 / $nof_items_total), $html);
            } else {
                $html = str_replace('{td_width}', 0, $html);
            }

            // Если задано, то переопределить ширину блока
            if (isset($options['block_width']) && !empty($options['block_width'])) {
                $html = str_replace('{block_style_custom}', 'style="width: ' . $options['block_width'] . '!important;"', $html);
            }
        }

        unset($block_tpl_parts, $block_tpl, $items, $item, $item_tpl, $item_container_tpl);

        // Вставной блок в конце блока
        if (isset($this->_block_ins_afterblock)) {
            $html .= $this->_block_ins_afterblock;
        }

        //Заполняем оставшиеся модификаторы значениями
        unset($options['block_no_css'], $options['block_orientation'], $options['block_width']);

        $tpl_modifiers = array_keys($options);
        foreach ($tpl_modifiers as $k => $m) {
            $tpl_modifiers[$k] = '{' . $m . '}';
        }
        unset($m, $k);

        $tpl_modifiers_values = array_values($options);

        $html = str_replace($tpl_modifiers, $tpl_modifiers_values, $html);
        unset($tpl_modifiers, $tpl_modifiers_values);

        //Очищаем незаполненные модификаторы
        $clear_modifiers_regexp = '#\{[a-z\d_\-]+\}#';
        $html = preg_replace($clear_modifiers_regexp, ' ', $html);

        $return_links_options = array(
            'is_block_links' => true,
            'nof_links_requested' => $n_requested,
            'nof_links_displayed' => $n,
            'nof_obligatory' => ($need_show_obligatory_block == true ? 1 : 0),
            'nof_conditional' => $nof_conditional
        );

        $html = $this->returnArrayLinksHtml($html, $return_links_options);

        return $this->returnHtml($html);
    }

    /**
     * Обработка html для массива ссылок
     *
     * @param string $html
     * @return string
     */
    protected function returnArrayLinksHtml($html, $options = null)
    {

        if (empty($options)) {
            $options = array();
        }

        // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
        if (
            strlen($this->_charset) > 0 &&
            strlen($this->_sape_charset) > 0 &&
            $this->_sape_charset != $this->_charset &&
            function_exists('iconv')
        ) {
            $new_html = @iconv($this->_sape_charset, $this->_charset, $html);
            if ($new_html) {
                $html = $new_html;
            }
        }

        if ($this->_is_our_bot) {

            $html = '<sape_noindex>' . $html . '</sape_noindex>';

            if (isset($options['is_block_links']) && true == $options['is_block_links']) {

                if (!isset($options['nof_links_requested'])) {
                    $options['nof_links_requested'] = 0;
                }
                if (!isset($options['nof_links_displayed'])) {
                    $options['nof_links_displayed'] = 0;
                }
                if (!isset($options['nof_obligatory'])) {
                    $options['nof_obligatory'] = 0;
                }
                if (!isset($options['nof_conditional'])) {
                    $options['nof_conditional'] = 0;
                }

                $html = '<sape_block nof_req="' . $options['nof_links_requested'] .
                    '" nof_displ="' . $options['nof_links_displayed'] .
                    '" nof_oblig="' . $options['nof_obligatory'] .
                    '" nof_cond="' . $options['nof_conditional'] .
                    '">' . $html .
                    '</sape_block>';
            }
        }

        return $html;
    }

    /**
     * Финальная обработка html перед выводом ссылок
     *
     * @param string $html
     * @return string
     */
    protected function returnHtml($html)
    {

        if ($this->_debug) {
            $html .= print_r($this, true);
        }

        return $html;
    }

    protected function getDispenserPath()
    {
        return '/code.php?user=' . _SAPE_USER . '&host=' . $this->_host;
    }

    protected function setData($data)
    {
        if ($this->_ignore_case) {
            $this->_links = array_change_key_case($data);
        } else {
            $this->_links = $data;
        }
        if (isset($this->_links['__sape_delimiter__'])) {
            $this->_links_delimiter = $this->_links['__sape_delimiter__'];
        }
        // определяем кодировку кеша
        if (isset($this->_links['__sape_charset__'])) {
            $this->_sape_charset = $this->_links['__sape_charset__'];
        } else {
            $this->_sape_charset = '';
        }
        if (@array_key_exists($this->_request_uri, $this->_links) && is_array($this->_links[$this->_request_uri])) {
            $this->_links_page = $this->_links[$this->_request_uri];
        } else {
            if (isset($this->_links['__sape_new_url__']) && strlen($this->_links['__sape_new_url__'])) {
                if ($this->_is_our_bot || $this->_force_show_code) {
                    $this->_links_page = $this->_links['__sape_new_url__'];
                }
            }
        }

        // Есть ли флаг блочных ссылок
        if (isset($this->_links['__sape_show_only_block__'])) {
            $this->_show_only_block = $this->_links['__sape_show_only_block__'];
        } else {
            $this->_show_only_block = false;
        }

        // Есть ли шаблон для красивых ссылок
        if (isset($this->_links['__sape_block_tpl__']) && !empty($this->_links['__sape_block_tpl__']) && is_array($this->_links['__sape_block_tpl__'])) {
            $this->_block_tpl = $this->_links['__sape_block_tpl__'];
        }

        // Есть ли параметры для красивых ссылок
        if (isset($this->_links['__sape_block_tpl_options__']) && !empty($this->_links['__sape_block_tpl_options__']) && is_array($this->_links['__sape_block_tpl_options__'])) {
            $this->_block_tpl_options = $this->_links['__sape_block_tpl_options__'];
        }

        // IDNA-домены
        if (isset($this->_links['__sape_block_uri_idna__']) && !empty($this->_links['__sape_block_uri_idna__']) && is_array($this->_links['__sape_block_uri_idna__'])) {
            $this->_block_uri_idna = $this->_links['__sape_block_uri_idna__'];
        }

        // Блоки
        $check_blocks = array(
            'beforeall',
            'beforeblock',
            'afterblock',
            'itemobligatory',
            'itemconditional',
            'afterall'
        );

        foreach ($check_blocks as $block_name) {

            $private_name = '__sape_block_ins_' . $block_name . '__';
            $prop_name = '_block_ins_' . $block_name;

            if (isset($this->_links[$private_name]) && strlen($this->_links[$private_name]) > 0) {
                $this->$prop_name = $this->_links[$private_name];
            }
        }
    }

}
