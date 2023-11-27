<?php
/*
Plugin Name: Star Wars Starships
Description: Display Star Wars starships on a select page.
Version: 1.0
Author: Avi Aminov
*/

// Include the constants file
include_once(plugin_dir_path(__FILE__) . 'constants.php');

// Include the code for the Starships class
include_once(plugin_dir_path(__FILE__) . 'starships-class.php');

// Create an instance of the Starships class
$starships = new Starships();

// Initialize the Starships class
$starships->init();
