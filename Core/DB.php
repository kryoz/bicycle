<?php

/**
 * PDO wrapper class
 * @author kryoz
 */

namespace Core;

class DB implements ServiceLocator\IService
{
    use TSingleton;

	const ERR_NO_CONNECTION = 'PDO connection fail';
	const ERR_INIT_FAIL = 'PDO init error';
	const ERR_SQL_ERROR = 'Query execution error';

	protected $scheme;
	protected $db;
	protected $user;
	protected $pass;

	/**
	 * @var \PDO
	 */
	protected $dbh;
	protected $result;

	/**
	 * @param string $scheme
	 * @param string $db
	 * @param string $user
	 * @param string $pass
	 */
	public function __construct($scheme = SETTINGS_DB_SCHEME, $db = SETTINGS_DB_ADDRESS, $user = SETTINGS_DB_USER, $pass = SETTINGS_DB_PASS)
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
		return 'db';
	}

	/**
	 * @param $sql
	 * @param array $params
	 * @param int $fetchFlags
	 * @return array
	 * @throws \PDOException
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
			throw new \PDOException(
                self::ERR_SQL_ERROR . ': ' . $e->getMessage()
                ."\n".'QUERY: ' . $sql ."\n".'PARAMS: ' . print_r($params, 1)
                );
		}

		return $result;
	}

	/**
	 * @param $sql
	 * @param array $params
	 * @return $this
	 * @throws \PDOException
	 */
	public function get($sql, array $params = [])
	{
		$this->checkConnection();

		try {
			$this->result = $this->dbh->prepare($sql);
			$this->result->execute($params);
		} catch (\PDOException $e) {
			throw new \PDOException(
                self::ERR_SQL_ERROR.': '.$e->getMessage()."\n".'QUERY: ' . $sql ."\n".'PARAMS: '
                . print_r($params, 1)
            );
		}

		return $this;
	}

	/**
	 * @param $fetchFlags
	 * @return mixed
	 * @throws \PDOException
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
	 * @param $sql
	 * @param array $params
	 * @return string
	 * @throws \PDOException
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
            throw new \PDOException(
                self::ERR_SQL_ERROR.': '.$e->getMessage()."\n".'QUERY: ' . $sql ."\n".'PARAMS: '
                . print_r($params, 1)
            );
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

    private function isSQlite()
    {
        return ($this->scheme == 'sqlite');
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

				$this->dbh->exec('SET NAMES ' . SETTINGS_INNERCODEPAGE);

				$serverVersion = $this->dbh->getAttribute(\PDO::ATTR_SERVER_VERSION);
				$emulate_prepares = version_compare($serverVersion, '5.1.17', '<');

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
