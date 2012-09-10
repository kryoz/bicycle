<?php
/**
 * Database class 
 * Initialization via dbDBO::getInstance()
 * @author kubintsev
 */

class DB
{
    private $dbh;
    private $transaction;
    private static $instance;
    
    /**
     * 
     * @param string $scheme db type scheme
     * @param string $db address string for connection
     * @throws Exception
     */
    private function __construct($scheme = SCHEME, $db = DBADRESS, $user = DBUSER, $pass = DBPASS) 
    {
        
        try 
        {
            if ( $scheme == 'sqlite' && !file_exists($db)) {
                throw new Exception("Database {$db} is not accessible!");
            }

            $this->dbh = new PDO($scheme . ':' . $db, $user, $pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            die('SQL error: '.$e->getMessage());
        }
    }

    function __destruct() 
    {
        $this->commit();
        
        unset($this->dbh);
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
     * query with data return
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
            Debug::log('SQL error: '.$e->getMessage().(DEBUG ? '<br>QUERY: '.$sql : ''));
        }
        
        return $result;
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
            Debug::log('SQL error: '.$e->getMessage().(DEBUG ? '<br>QUERY: '.$sql : '') );
        }
        
        return $this->dbh->lastInsertId();
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
    
    public function begin()
    {
        if (!$this->transaction)
        {
            $this->exec('BEGIN');
            $this->transaction = true;
        }  
    }
    
    public function commit()
    {
        if ($this->transaction)
        {
            $this->exec('COMMIT');
            $this->transaction = false;
        }
    }
}
