<?php
/**
 * Database server connectivity and database selection
 * Makes use of the Singleton pattern by creating only one mysqli instance
 *
 * @author Sethathi <sethathi@gmail.com>
 */
class Database {

    /**
     * The database server IP
     *
     * @var String
     * @access private           
     */
    private $_host;

    /**
     * The username for connecting to the database server
     * 
     * @var String
     * @access private
     */
    private $_username;

    /**
     * The password for connecting to the database server
     * 
     * @var String
     * @access private
     */
    
    private $_password;

    /**
     * The database name
     * This is the Database we are going to use
     * 
     * @var String
     * @access private
     */
    private $_db_name;

    /**
     * The mysqli instance we are going to use in this class
     *
     * @var mysqli instance
     * @access private
     */
    private $_db_connection = NULL;
    private $_db_tables;
    private $_db_data;

    /**
     * The Database instance (of this class) we are going to use throughout or application. Only one instance of it will be created
     *
     * @var Database instance
     * @access private
     */
    private static $_instance = NULL;

    /**
     * Constructor
     * Our constructor is private (can only be called from this class) so it cannot be instantiated multiple times
     * A connection to the database server will be established by calling the connection() method
     *
     * @access private
     */
    private function __construct()
    {
        require_once('../__database__/database.conf');
        //database config
        $this->_host = $database_conf['host'];
        $this->_username = $database_conf['username'];
        $this->_password = $database_conf['password'];
        $this->_db_name = $database_conf['database'];
        $this->_db_tables = $database_tables;
        $this->_db_data = $database_data;
        //for now, recreate DB every time
        $this->recreate_db();
        $this->connection();
    }

    /**
    * 
    * @return Database object we are going to use throughout our application. Create it if necessary
     *
     * @access public
    */
    public static function get_instance() {
        if ( ! isset(self::$_instance)) {
            //echo "Creating new instance.\n";
            $class_name = __CLASS__;
            self::$_instance = new $class_name;
        }
        return self::$_instance;
    }

    /**
     * This function recreates the database from the database.conf file
     *
     * @access private
     */
    private function recreate_db()
    {
        echo "Re-creating database.\n";
        try 
        {
            //create or open the database
            $this->_db_connection = new SQLiteDatabase('../__database__/'.$this->_db_name.'.sqlite', 0666, $error);
            //create tables
            foreach($this->_db_tables as $table => $columns)
            {
                //drop table first
                if ( ! $this->_db_connection->queryExec('DROP TABLE '.$table, $error))
                {
                    throw new Exception($error);
                }
                
                //create table
                $query = 'CREATE TABLE ' . $table . '(';
                foreach($columns as $name => $type)
                {
                    $query .= $name.' '.$type.',';
                }
                //remove last trailing comma
                $query = substr($query, 0, -1).')';
                         
                if ( ! $this->_db_connection->queryExec($query, $error))
                {
                    throw new Exception($error);
                }
            }
            
            //insert data
            foreach($this->_db_data as $table => $table_data)
            {
                foreach($table_data as $data)
                {
                    $query = 'INSERT INTO ' . $table . '(' . $data['fields'] . ') VALUES (' . $data['values'] . ')';
                    if ( ! $this->_db_connection->queryExec($query, $error))
                    {
                        throw new Exception($error);
                    }
                }
            }
        }
        catch (Exception $e)
        {
            echo $error."\n";
        }
    }

    /**
     * This function establishes a connection to the database server
     *  and selects a database we are going to use
     *
     * @access private
     */
    private function connection() {
        //echo "Creating new connection.\n";
        try {
            //The mysqli object will be created once and only once
        	//if ( ! $this->_db_connection instanceof mysqli) {
            //	$this->_db_connection = new mysqli($this->_host, $this->_username, $this->_password);
        	//}
            //if ($this->_db_connection->connect_errno) {
            //    throw new Exception('An error occured: ' . $this->_db_connection->connect_error);
            //} else {
            //    $this->select_db();
            //}
        } catch (Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    /**
     * Here this function selects a database we are going to use 
     * 
     * @access private 
     */
    private function select_db() {
        //echo "Selecting database.\n";
        try {
            $this->_db_connection->select_db($this->_db_name) or die('Could not find database');
        } catch (Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    /**
     * This function runs a query using our database connection. Input sanitation, etc. must be done
     * 
     * @access public 
     */
    public function query($query) {
        if ( ! isset($query))
        {
            return FALSE;
        }
        //echo "Prevent SQL Injection. \n"
        //$query = $this->_db_connection->real_escape_string($query);
        return $this->_db_connection->query($query);
    }

    /**
    * This function creates a mysqli object for use with mysqli prepare
    * 
    * @access public
    * @return return mysqli prepared statement object
    */
    public function stmt_init(){
        return $this->_db_connection->stmt_init();
    }

    

    /**
     * Here we close the connection to the database server with the  destructor
     */
    public function __destruct() {
    	if ($this->_db_connection instanceof mysqli) {
            $this->_db_connection->close();
        }
    }
    
    /**
     * Not allowed to clone a Singleton
     * 
     * @access private 
     */
    private function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
    
    /**
     * Singletons should be unserializable
     * 
     * @access private 
     */
    private function __wakeup() {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }
}


?>
