<?php

/**
 * PDO wrapper class
 * @author kryoz
 */

namespace Core;

class DB implements ServiceLocator\IService
{

	const ERR_NO_CONNECTION = 'PDO connection fail';
	const ERR_INIT_FAIL = 'PDO init error';
	const ERR_SQL_ERROR = 'Query execution error';

	protected $scheme;
	protected $db;
	protected $user;
	protected $pass;

	/**
	 *
	 * @var \PDO
	 */
	protected $dbh;
	protected $result;
	protected static $instance;

	/**
	 *
	 * @param string $scheme db type scheme
	 * @param string $db address string for connection
	 * @throws Exception
	 */
	public function __construct($scheme = SCHEME, $db = DBADDRESS, $user = DBUSER, $pass = DBPASS)
	{
		$this->scheme = $scheme;
		$this->db = $db;
		$this->user = $user;
		$this->pass = $pass;
	}

	public function __destruct()
	{
		unset($this->dbh);
	}

	public function getServiceName()
	{
		return 'DB';
	}

	private function isSQlite()
	{
		return ($this->scheme == 'sqlite');
	}

	/**
	 *
	 * @return DB
	 */
	public static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * query with fetching all data in return
	 * @param string $sql
	 * @param array $params
	 * @param int $fetchFlags
	 * @return array
	 */
	public function query($sql, array $params = array(), $fetchFlags = \PDO::FETCH_ASSOC)
	{
		$this->checkConnection();

		try {
			$sth = $this->dbh->prepare($sql);
			$sth->execute($params);
			$result = $sth->fetchAll($fetchFlags);
			$sth->closeCursor();
		} catch (\PDOException $e) {
			if (!DEBUG) {
                Debug::log(self::ERR_SQL_ERROR . ': ' . $e->getMessage());
				Debug::log('QUERY: ' . $sql);
				Debug::log('PARAMS: ' . print_r($params, true));
			}
			throw new \PDOException(
                self::ERR_SQL_ERROR . ': ' . $e->getMessage().
                "\n".'QUERY: ' . $sql ."\n".'PARAMS: ' . print_r($params, true)
                );
		}

		return $result;
	}

	/**
	 * Perform query without fetching it
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public function get($sql, array $params = array())
	{
		$this->checkConnection();

		try {
			$this->result = $this->dbh->prepare($sql);
			$this->result->execute($params);
		} catch (\PDOException $e) {
			if (DEBUG) {
				Debug::log('QUERY: ' . $sql);
				Debug::log('PARAMS: ' . print_r($params, true));
			}
			throw new \PDOException(self::ERR_SQL_ERROR . ': ' . $e->getMessage());
		}

		return $result;
	}

	/**
	 * fetch a row from query result $this->get()
	 * @param int $fetchFlags
	 * @return array
	 */
	public function fetchRow($fetchFlags = PDO::FETCH_ASSOC)
	{
		$this->checkConnection();

		if (empty($this->result)) {
			return;
		}

		try {
			return $this->result->fetch($fetchFlags);
		} catch (\PDOException $e) {
			throw new \PDOException(self::ERR_SQL_ERROR . ': ' . $e->getMessage());
		}
	}

	/**
	 * query without data return
	 * @param string $sql
	 * @param array $params
	 * @return int last row_id
	 */
	public function exec($sql, array $params = array())
	{
		$this->checkConnection();

		try {
			$sth = $this->dbh->prepare($sql);
			$sth->execute($params);
			$sth->closeCursor();
			unset($sth);
		} catch (PDOException $e) {
			if (DEBUG) {
				Debug::log('QUERY: ' . $sql);
				Debug::log('PARAMS: ' . print_r($params, true));
			}
			throw new \PDOException(self::ERR_SQL_ERROR . ': ' . $e->getMessage());
		}

		return $this->dbh->lastInsertId();
	}

	public function begin()
	{
		$this->checkConnection();

		$this->dbh->beginTransaction();
	}

	public function commit()
	{
		$this->checkConnection();

		$this->dbh->commit();
	}

	/**
	 * Make explain for query
	 * @param type $sql
	 * @param array $params
	 * @return string
	 */
	public function explain($sql, array $params = array())
	{
		$res = $this->query($sql, $params);

		if (empty($res))
			return;

		$html = '<table width="100%" border="1">';

		foreach ($res as $i => $row) {
			$html .= "<tr>";

			if ($i != 0) {
				foreach ($row as $k => $v) {
					$html .= "<td>$v</td>";
				}
			} else {
				foreach ($row as $k => $v) {
					$html .= "<th>$k</th>";
				}
			}

			$html .= "</tr>";
		}

		$html .= '</table>';

		return $html;
	}

	/**
	 * Get DB PDO object
	 * @return mixed
	 */
	public function o()
	{
		$this->checkConnection();

		return $this->dbh;
	}

	protected function checkConnection()
	{
		if(empty($this->dbh)) {
			$this->init();
		}
	}

	protected function init()
	{
		try {
			if (!$this->isSQlite()) {
				$this->dbh = new \PDO($this->scheme . ':' . $this->db, $this->user, $this->pass);

				$this->dbh->exec('SET NAMES ' . INNERCODEPAGE);

				$serverversion = $this->dbh->getAttribute(\PDO::ATTR_SERVER_VERSION);
				$emulate_prepares = version_compare($serverversion, '5.1.17', '<');

				$this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, $emulate_prepares);
			}
			else
				$this->dbh = new \PDO($this->scheme . ':' . $this->db);

			$this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			throw new \PDOException(self::ERR_INIT_FAIL . ': ' . $e->getMessage());
		}
	}
}
