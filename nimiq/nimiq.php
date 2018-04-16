<?php
/*
Plugin Name: Nimiq Miner
Plugin URI: https://github.com/pom75/nimiqWP
Description: A plugin to mine nim to any address that the user specifie. Look at https://github.com/pom75/nimiqWP for more information.
Version: 1.1
Author URI: https://github.com/pom75
License: GPL3
*/

add_action('wp_enqueue_scripts','ava_test_init');

function ava_test_init() {
	wp_enqueue_script( 'nimiq1', 'http://cdn.nimiq.com/nimiq.js');
	wp_enqueue_script( 'nimiq2', 'http://cdn.nimiq.com/web.js');
	wp_enqueue_script( 'nimiq3', 'http://cdn.nimiq.com/worker-wasm.js');
    wp_enqueue_script( 'nimiq4', plugins_url( './js/nimiq.js', __FILE__ ),array('jquery'));
	
}

?>