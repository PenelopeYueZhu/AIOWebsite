<?php
/**
 * File that defines Database object and it's related functions
 */

/**
 * Class database. It is essentially a link to the sql database that holds all
 * the related data.
 */
class Database {

	$database; // This is the database we will be using throughout the class

	/**
	 * init function. Establish the link between the web client and database
	 */
	public function init(){
		// Get the information we need from the private folder
		$config = parse_ini_file( '../../private/connection.ini');
		$server = 'p:' . $config['servername'];
		$username   = $config['username'];
		$password   = $config['password'];
		$database   = 'test_forum_db';

		// initialize the database object
		$database = mysqli_connect($server, $username,$password, $database);
	}

	/**
	 * Get all the questions stored in the database
	 * Will determine the user's permission with $_SESSION, instead of
	 * passed in parameter
	 * @return
	 */

}
?>
