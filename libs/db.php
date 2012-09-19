<?php
/**
 * Database class 
 * Initialization via dbDBO::getInstance()
 * @author kubintsev
 */

class DB
{
    private $scheme;
    private $dbh;
    private $result;
    private static $instance;
    
    /**
     * 
     * @param string $scheme db type scheme
     * @param string $db address string for connection
     * @throws Exception
     */
    function __construct($scheme = SCHEME, $db = DBADRESS, $user = DBUSER, $pass = DBPASS) 
    {
        $this->scheme = $scheme;

        try 
        {
            if (!$this->isSQlite())
            {
                $this->dbh = new PDO($scheme . ':' . $db, $user, $pass);

                /* http://stackoverflow.com/questions/10113562/pdo-mysql-use-pdoattr-emulate-prepares-or-not */
                
                if ( version_compare(PHP_VERSION, '5.3.6', '<') )
                    $this->dbh->exec('SET NAMES '.INNERCODEPAGE);

                $serverversion = $this->dbh->getAttribute(PDO::ATTR_SERVER_VERSION);
                $emulate_prepares = version_compare($serverversion, '5.1.17', '<');
                $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, $emulate_prepares);
            }
            else
                $this->dbh = new PDO($scheme . ':' . $db);
            
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            Debug::log('PDO init error: '.$e->getMessage());
        }
    }

    function __destruct() 
    {
        unset($this->dbh);
    }
    
    private function isSQlite()
    {
        return ($this->scheme == 'sqlite');
    }
    
    public static function getInstance()
    {
        if ( empty( self::$instance))
        {
            self::$instance = new DB();
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
    public function query( $sql, array $params = array(), $fetchFlags = PDO::FETCH_ASSOC ) 
    {
        if (!$this->checkConnection())
            return false;
        
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute( $params );
            $result = $sth->fetchAll($fetchFlags);

            $sth->closeCursor();
        } 
        catch (PDOException $e) 
        {
            Debug::log('SQL error: '.$e->getMessage());
            
            if (DEBUG)
            {
                Debug::log('QUERY: '.$sql );
                Debug::log('PARAMS: '.print_r($params,true) );
            }
        }
        
        return $result;
    }
    
    
    /**
     * Perform query without fetching it
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function get( $sql, array $params = array() ) 
    {
        if (!$this->checkConnection())
            return false;
        try {
            $this->result = $this->dbh->prepare($sql);
            $this->result->execute( $params );
        } 
        catch (PDOException $e) 
        {
            Debug::log('SQL error: '.$e->getMessage());
            
            if (DEBUG)
            {
                Debug::log('QUERY: '.$sql );
                Debug::log('PARAMS: '.print_r($params,true) );
            }
        }
        
        return $result;
    }
    
    /**
     * fetch a row from query result $this->get()
     * @param int $fetchFlags
     * @return array
     */
    public function fetchRow( $fetchFlags = PDO::FETCH_ASSOC ) 
    {
        if (!$this->checkConnection() || empty($this->result))
            return false;
        
        try {
            return $this->result->fetch($fetchFlags);
        } 
        catch (PDOException $e) 
        {
            Debug::log('SQL error: '.$e->getMessage());
        }

    }

    /**
     * query without data return
     * @param string $sql
     * @param array $params
     * @return int last row_id
     */
    public function exec( $sql, array $params = array() ) 
    {
        if (!$this->checkConnection())
            return false;
        
        try {
            $this->dbh->prepare($sql)->execute( $params );
        } 
        catch (PDOException $e) {
            Debug::log('SQL error: '.$e->getMessage());
            
            if (DEBUG)
            {
                Debug::log('QUERY: '.$sql );
                Debug::log('PARAMS: '.print_r($params,true) );
            }
        }
        
        return $this->dbh->lastInsertId();
    }
    
    public function begin()
    {
        if (!$this->checkConnection())
            return false;
        
        $this->dbh->beginTransaction();
    }
    
    public function commit()
    {
        if (!$this->checkConnection())
            return false;
        
        $this->dbh->commit();
    }
    
    public function explain( $sql, array $params = array())
    {
        $res = $this->query($sql, $params);
        $html = '<table width="100%" border="1">';
        
        foreach ($res as $i=>$row)
        {
            $html .= "<tr>";
            
            if ( $i != 0 )
            {
                foreach ($row as $k=>$v)
                {
                    $html .= "<td>$v</td>";
                }
            } else {
                foreach ($row as $k=>$v)
                {
                    $html .= "<th>$k</th>";
                }
            }
            
            $html .= "</tr>";
        }
        
        $html .= '</table>';
        
        return $html;
    }
    
    public function checkConnection()
    {
        if (empty($this->dbh))
        {
            Debug::log('DB CLASS: Failed to connect to database!');
            return false;
        }   else
            return true;
    }
    
    public function o()
    {
        if (!$this->checkConnection())
            return false;
        else
            return $this->dbh;
    }
}
