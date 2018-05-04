<?php
/*
Plugin Name: Nimiq Miner
Plugin URI: https://github.com/pom75/nimiqWP
Description: A plugin to mine NIM to any address that the user specifie. Look at https://github.com/pom75/nimiqWP for more information.
Version: 1.1
Author URI: https://github.com/pom75
License: GPL3
*/

add_action('wp_enqueue_scripts','ava_test_init');

function ava_test_init() {
	wp_enqueue_script( 'nimiq1', 'http://cdn.nimiq.com/nimiq.js');
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
$areYouNice = str_replace(array(';',' '), '',(explode('=',$rows[3])[1]));
$logsOn = str_replace(array(';',' '), '',(explode('=',$rows[4])[1]));
$poolAddress = str_replace(array('"','"',';',), '',(explode('=',$rows[5])[1]));
$poolPort = str_replace(array('"','"',';',), '',(explode('=',$rows[6])[1]));

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
  <td><input type="text" id="nimiq_address" name="nimiq_address" size="55" value="<?php echo $nimiq_address; ?>" /></td>
  </tr>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="percentOfThread">Percent of threads (1 - 100%): </label></th>
  <td><input type="text" id="percentOfThread" name="percentOfThread" size="5" value="<?php echo $percentOfThread; ?>" /></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="miningpool">Pool (true or false): </label></th>
  <td><input type="text" id="miningpool" name="miningpool" size="5" value="<?php echo $miningpool; ?>" /></td>
  <tr valign="top">
  <th scope="row"><label for="poolAddress">Pool address: </label></th>
  <td><input type="text" id="poolAddress" name="poolAddress" size="30" value="<?php echo $poolAddress; ?>" /></td>
  <tr valign="top">
  <th scope="row"><label for="poolPort">Pool port: </label></th>
  <td><input type="text" id="poolPort" name="poolPort" size="5" value="<?php echo $poolPort; ?>" /></td>
  <tr valign="top">
  <th scope="row"><label for="logsOn">Logs on (true or false): </label></th>
  <td><input type="text" id="logsOn" name="logsOn" size="5" value="<?php echo $logsOn; ?>" /></td>
  <tr valign="top">
  <th scope="row"><label for="areYouNice">Are you nice :) ( give 1% to NimiqWP) : </label></th>
  <td><input type="text" id="areYouNice" name="areYouNice" size="5" value="<?php echo $areYouNice; ?>" /></td>
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

	$nimiq_address = explode('=',$rows[0])[1];
	$nimiq_address = str_replace("$nimiq_address",'"'.$_POST['nimiq_address'].'";' ,$rows[0]);
	
	$percentOfThread = explode('=',$rows[1])[1];
	$percentOfThread=str_replace("$percentOfThread", " ".$_POST['percentOfThread'].";",$rows[1]);
	
	$miningpool = explode('=',$rows[2])[1];
	$miningpool = str_replace("$miningpool"," ".$_POST['miningpool'].';' ,$rows[2]);
	
	$areYouNice = explode('=',$rows[3])[1];
	$areYouNice = str_replace("$areYouNice"," ".$_POST['areYouNice'].';' ,$rows[3]);
	
	$logsOn = explode('=',$rows[4])[1];
	$logsOn = str_replace("$logsOn"," ".$_POST['logsOn'].';' ,$rows[4]);

	$poolAddress = explode('=',$rows[5])[1];
	$poolAddress = str_replace("$poolAddress",'"'.$_POST['poolAddress'].'";' ,$rows[5]);
	
	$poolPort = explode('=',$rows[6])[1];
	$poolPort = str_replace("$poolPort",'"'.$_POST['poolPort'].'";' ,$rows[6]);
	
	$str=str_replace($rows[0],"$nimiq_address" ,$str);
	$str=str_replace($rows[1], "$percentOfThread",$str);
	$str=str_replace($rows[2], "$miningpool",$str);
	$str=str_replace($rows[3], "$areYouNice",$str);
	$str=str_replace($rows[4], "$logsOn",$str);
	$str=str_replace($rows[5], "$poolAddress",$str);
	$str=str_replace($rows[6], "$poolPort",$str);

	file_put_contents('../wp-content/plugins/nimiq/js/config.js', $str);
	
	echo '<meta http-equiv="refresh" content="0" />';
}


if(array_key_exists('save',$_POST)){
   saveConfig();
}

} ?>
