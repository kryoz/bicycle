<?php
/**
 * ����� ������ � �� SQLite ����� PDO
 * �������� ������� ����� dbDBO::getInstance()
 * @author �������� �.�.
 */

class dbPDO
{
    private $dbh;
    private $transaction;
    private static $instance;
    
    /**
     * 
     * @param string $scheme ��� ��
     * @param string $db �������� ������ ��� ����������� ��
     * @throws Exception
     */
    function __construct($scheme = SCHEME, $db = DBADRESS, $user = DBUSER, $pass = DBPASS) 
    {
        if ( $scheme == 'sqlite' && !file_exists($db)) {
            throw new Exception("Database {$db} is not accessible!");
        }
        
        try {
            $this->dbh = new PDO($scheme . ':' . $db, $user, $pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            Debug::log('SQL error: '.$e->getMessage());
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
            self::$instance = new dbPDO();
        } 
        
        return self::$instance;
        
    }
    
    /**
     * ������ � ���������� ������
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
     * ������ ��� ��������� ������
     * @param string $sql
     * @param array $params
     * @return int ���������� �������� ���������� row_id
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
