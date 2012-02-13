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
    private $_password = '';

    /**
     * The database name
     * This is the Database we are going to use
     * 
     * @var String
     * @access private
     */
    private $_db_name = 'viasms';

    /**
     * The mysqli instance we are going to use throughout or application. Only one instance of it will be created
     *
     * @var mysqli instance
     * @access private
     */
    protected static $_mysqli = NULL;



    /**
     * Constructor
     * Our constructor will initiate the mysqli object 
     * Afterwards a connection to the database server will be established after calling the connection()
     *
     * @access public
     */

    function __construct() {
        //The mysqli object will be created once and only once
    	if(!(self::$_mysqli instanceof mysqli))
        	self::$_mysqli = new mysqli();
        $this->connection();
    }

    /**
     * This function establishes a connection to the database server
     *  and selects a database we are going to use
     *
     * @access public
     */

    function connection() {
        try {
            if (!self::$_mysqli->connect($this->_host, $this->_username, $this->_password))
                $this->selectDB();
            else {
                throw new Exception('An error occured ' . self::$_mysqli->connect_error);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Here this function selects a database we are going to use 
     * 
     * @access public 
     */

    function selectDB() {
        try {
            self::$_mysqli->select_db($this->_db_name) or die('Could not find database');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
    * 
    * @return $_mysqli object we are going to use throughout our application
    */
    static function getInstance(){
    	return self::$_mysqli;
    }

    /**
     * Here we close the connection to the database server with the  destructor
     */
    function __destruct() {
            //self::$_mysqli->close();
    }

}


?>