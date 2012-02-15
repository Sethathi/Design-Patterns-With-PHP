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
    private $_host = 'localhost';

    /**
     * The username for connecting to the database server
     * 
     * @var String
     * @access private
     */
    private $_username = 'root';

    /**
     * The password for connecting to the database server
     * 
     * @var String
     * @access private
     */
    private $_password = '3628800sql';

    /**
     * The database name
     * This is the Database we are going to use
     * 
     * @var String
     * @access private
     */
    private $_db_name = 'mysql';

    /**
     * The mysqli instance we are going to use in this class
     *
     * @var mysqli instance
     * @access private
     */
    private $_mysqli = NULL;

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
    private function __construct() {
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
     * This function establishes a connection to the database server
     *  and selects a database we are going to use
     *
     * @access private
     */
    private function connection() {
        //echo "Creating new connection.\n";
        try {
            //The mysqli object will be created once and only once
        	if ( ! $this->_mysqli instanceof mysqli) {
            	$this->_mysqli = new mysqli($this->_host, $this->_username, $this->_password);
        	}
            if ($this->_mysqli->connect_errno) {
                throw new Exception('An error occured: ' . $this->_mysqli->connect_error);
            } else {
                $this->select_db();
            }
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
            $this->_mysqli->select_db($this->_db_name) or die('Could not find database');
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
        return $this->_mysqli->query($query);
    }

    /**
     * Here we close the connection to the database server with the  destructor
     */
    public function __destruct() {
    	if ($this->_mysqli instanceof mysqli) {
            $this->_mysqli->close();
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
