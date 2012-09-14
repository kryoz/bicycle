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
        
        if ( $this->isSQlite() && !file_exists($db)) 
        {
            throw new Exception("Database {$db} is not accessible!");
        }

        try 
        {
            if (!$this->isSQlite())
                $this->dbh = new PDO($scheme . ':' . $db, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.INNERCODEPAGE));
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
        $this->dbh->beginTransaction();
    }
    
    public function commit()
    {
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
    
    public function o()
    {
        return $this->dbh;
    }
}
