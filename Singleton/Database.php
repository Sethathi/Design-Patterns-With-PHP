<?php
/**
 * Database server connectivity and database selection
 * Makes use of the Singleton pattern by creating only one mysqli instance
 *
 * @author Sethathi <sethathi@gmail.com>
 */
class Database
{
	/**
	 * The database config settings
	 * This is the Database we are going to use
	 * 
	 * @var Array
	 * @access private
	 */
	private $_db_conf;

	/**
	 * The PDO database handle we are going to use in this class
	 *
	 * @var mysqli instance
	 * @access private
	 */
	private $_db_handle = NULL;
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
		$this->_db_conf = $database_conf;
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
	public static function get_instance()
	{
		if ( ! isset(self::$_instance))
		{
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
		//echo "Re-creating database.\n";
		try 
		{
			//remove DB file if exists (because we can't drop table if exists)
			$db_file = '../__database__/'.$this->_db_conf['database'].'.sqlite';
			if (file_exists($db_file))
			{
				unlink($db_file);
			}
			//create or open the database
			$this->connection();
			//create tables
			foreach($this->_db_tables as $table => $columns)
			{
				//drop table first (only supported by SQLite >= 3.3
		/*
				if ( ! $this->_db_handle->queryExec('DROP TABLE IF EXISTS '.$table, $error))
				{
					throw new Exception($error);
				}
		*/
				
				//create table
				$query = 'CREATE TABLE ' . $table . '(';
				foreach($columns as $name => $type)
				{
					$query .= $name.' '.$type.',';
				}
				//remove last trailing comma
				$query = substr($query, 0, -1).')';
						 
				$this->_db_handle->exec($query);
			}
			
			//insert data
			foreach($this->_db_data as $table => $table_data)
			{
				foreach($table_data as $data)
				{
					$query = 'INSERT INTO ' . $table . '(' . $data['fields'] . ') VALUES (' . $data['values'] . ')';
					$this->_db_handle->exec($query);
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage()."\n";
		}
	}

	/**
	 * This function establishes a connection to the database server
	 *  and selects a database we are going to use
	 *
	 * @access private
	 */
	private function connection()
	{
		//echo "Creating new connection.\n";
		try
		{
			//create or open the database
			$this->_db_handle = new PDO($this->_db_conf['connection']);
		}
		catch (Exception $e)
		{
			echo $e->getMessage()."\n";
		}
	}

	/**
	 * This function runs a query using our database connection. Input sanitation, etc. must be done
	 * 
	 * @access public 
	 */
	public function query($query, $bind_params = NULL)
	{
		//echo "Prevent SQL Injection. \n"
		//$query = $this->_db_handle->real_escape_string($query);
		$stm = $this->_db_handle->prepare($query);
		if (is_array($bind_params) && ! empty($bind_params))
		{
			return $stm->execute($bind_params);
		}
		return $this->_db_handle->query($query);
	}

	/**
	* This function creates a PDOStatement object
	* 
	* @access public
	* @return return PDOStatement object
	*/
	public function prepare($query)
	{
		return $this->_db_handle->prepare($query);
	}

	/**
	 * Here we close the connection to the database server with the  destructor
	 */
	public function __destruct()
	{
		$this->_db_handle = NULL;
	}
	
	/**
	 * Not allowed to clone a Singleton
	 * 
	 * @access private 
	 */
	private function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Singletons should be unserializable
	 * 
	 * @access private 
	 */
	private function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}
}
?>