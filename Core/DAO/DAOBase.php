<?php
namespace Core\DAO;

use Core\DB;
use Core\FixedArrayAccess;
use Core\ServiceLocator\Locator;

abstract class DAOBase extends FixedArrayAccess
{
    const ID = 'id';

    /**
     * @var DB
     */
    protected $db;

    /**
     * свойства объекта БД, которые непосредственно в нём не присутствуют
     */
    protected $fictiveProperties = array();

    /**
     * название таблицы БД
     */
    protected $table;

    public function __construct($propertyNames = null)
    {
        $properties = [self::ID];
        $properties = array_merge($properties, $propertyNames);
        $this->db = Locator::get('DB');

        parent::__construct($properties);
    }

    /**
     *
     * @return DAOBase
     */
    public static function create()
    {
        return new static();
    }

    public function getById($id)
    {
        if ($id) {
            $query = "SELECT * FROM {$this->table} WHERE id = ?";
            if ($data = $this->db->query($query, array($id))) {
                $this->fillParams($data[0]);
            }
        }

        return $this;
    }

    public function fillParams(array $params)
    {
        foreach ($params as $property => $value) {
            $this[$property] = $value;
        }
    }

    protected function addFictiveProperty($propertyName)
    {
        $this->addProperty($propertyName);
        $this->fictiveProperties = array_merge($this->fictiveProperties, array($propertyName => null));
    }

    /**
     *
     * @param string $query
     * @param array $params
     * @param string null|string
     * @return array
     */
    protected function getListByQuery($query, $params, $type = null)
    {
        $result = [];

        foreach ($this->db->query($query, $params) as $item) {
            if ($type) {
                $entity = new $type;
            } else {
                $entity = static::create();
            }
            $entity->fillParams($item);
            $result[] = $entity;
        }

        return $result;
    }

    /**
     * Возвращает хэш по содержимому объекта
     * @return string
     */
    public function getUID()
    {
        return md5(serialize($this->properties));
    }

    public function getId()
    {
        return $this[self::ID];
    }

    /**
     * сохраняет объект БД или создает новый
     */
    public function save()
    {
        $params = array_diff_key($this->properties, $this->fictiveProperties);
        if (!$this->getId()) {
            unset($params[self::ID]);
            $keys = array_keys($params);

            $query = "INSERT INTO {$this->table} (".implode(', ', $keys).") VALUES ";

            foreach ($keys as $key) {
                $placeholders[] = "?";
            }

            $query .= "(".implode(', ', $placeholders). ")";
            $this[self::ID] = $this->db->exec($query, array_values($params));
        } else {
            $query = "UPDATE {$this->table} SET ";
            $queryParts = [];

            foreach ($params as $key => $val) {
                $queryParts[] = "$key = ?";
            }

            $query .= implode(", ", $queryParts). " WHERE id = :".self::ID;
            $this->db->exec($query, array_values($params));
        }
    }

    public function __wakeup()
    {
        $this->db = Locator::get('DB');
    }

    public function __sleep()
    {
        return array('properties', 'propertyNames');
    }
}
