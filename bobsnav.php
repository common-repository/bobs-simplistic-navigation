<?php
/*
Plugin Name: Bob's Simplistic Navigation
Plugin URI: http://blog.reformatthis.com/plugins/bobsnav/
Description: Creates Navigation Links for Posts.  To be used when your set Theme is missing this feature and you want to add it easily.  Have you checked out <a href='http://blog.reformatthis.com' target='_blank'>blog.reformatthis.com</a> or <a href='http://samanathon.com' target='_blank'>samanathon.com</a> yet?  No?  You really should! Do you use this plugin and like it?  Please write a post about it and link back to my page or add me to your blogroll, I would be very honored.  Thanks!
Version: 1.0 "Goober"
Author: RJ "Bobs" Matthis
Author URI: http://blog.reformatthis.com
*/

/* Copyright 2007  RJ "Bobs" Matthis (email : plugins@blog.reformatthis.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
 
 // get current settings
 $bobsnav_pos_top = get_option("bobsnav_pos_top");
 $bobsnav_pos_mid = get_option("bobsnav_pos_mid");
 $bobsnav_pos_bot = get_option("bobsnav_pos_bot");
 
 // load all the shared items if any option is chosen
 switch (1) {
 	case $bobsnav_pos_top:
 	case $bobsnav_pos_mid:
 	case $bobsnav_pos_bot:
 		
		function bobs_simplistic_nav_styles() {
			if (is_single()) {
			?>
<!-- start of style sheet for Bob's Simplicity Navigation -->
<style type="text/css">
	@import url("<?php echo get_settings('siteurl'); ?>/wp-content/plugins/bobsnav/style.css");
</style>
<!-- end of style sheet for Bob's Simplicity Navigation -->
			<?php
			}
 		}
		
		// action to add style info to header
		add_action('wp_head', 'bobs_simplistic_nav_styles');
		
		function bobsnav_load_content() {
			if (is_single()) {
			?>
<div id="bobsnavplugin" class="bobsnav_container">
	<span class="bobsnav_alignleft" title="Click to Read the Previous Post"><?php previous_post_link('%link'); ?></span>
	<span class="bobsnav_alignright" title="Click to Read the Next Post"><?php next_post_link('%link'); ?></span>
</div>
			<?php
			}
		}
 		break;
 }
 
 // process position top if chosen
 if ($bobsnav_pos_top == 1) {
	add_action('loop_start', 'bobsnav_load_content', 1);
 }
 
 // process position mid if chosen
 if ($bobsnav_pos_mid == 1) {
	function bobsnav_load_mid_content($content) {
		if (is_single()) {
			ob_start();
			bobsnav_load_content();
			$content .= ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}
	add_action('the_content', 'bobsnav_load_mid_content', 999);
 }
 
 // process position bot if chosen
 if ($bobsnav_pos_bot == 1) {
	add_action('loop_end', 'bobsnav_load_content', 999);
 }
 
 // the function that is the content of the options page
 function bobsnav_optionspage() {
 	// output message
 	$message = "";
 	
 	if (isset($_POST['action'])) {
 		if ($_POST['action'] == 'update') {
 			if (isset($_POST['bobsnav_pos_top'])) {
 				update_option("bobsnav_pos_top", 1);
 			}
 			else {
 				update_option("bobsnav_pos_top", 0);
 			}
 			
 			if (isset($_POST['bobsnav_pos_mid'])) {
 				update_option("bobsnav_pos_mid", 1);
 			}
 			else {
 				update_option("bobsnav_pos_mid", 0);
 			}
 			
 			if (isset($_POST['bobsnav_pos_bot'])) {
 				update_option("bobsnav_pos_bot", 1);
 			}
 			else {
 				update_option("bobsnav_pos_bot", 0);
 			}
 			
 			$message = "Your Requested Updates Have Been Made";
 		}
 	}
 	//Print out the message to the user, if any
	if($message != "") {
		?>
		<div class="updated">
			<strong>
				<p>
					<?php echo $message; ?>
				</p>
			</strong>
		</div>
		<?php
	}
 	?>
	<div class="wrap">
		<h2><?php _e('Bob\'s Simplistic Navigation Options') ?></h2>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
			<input type="hidden" name="action" value="update" />
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				<tr valign="top"> 
					<th width="33%" scope="row">
						<?php _e('Navigation Links Position:') ?>
					</th> 
					<td>
						<input name="bobsnav_pos_top" type="checkbox"<?php if (get_option('bobsnav_pos_top') == '1') { echo " checked='checked'"; } ?> value="1">Very Top of Post (Before Title)<br />
						<input name="bobsnav_pos_mid" type="checkbox"<?php if (get_option('bobsnav_pos_mid') == '1') { echo " checked='checked'"; } ?> value="1">Middle of Post (After Content, Before Comments)<br />
						<input name="bobsnav_pos_bot" type="checkbox"<?php if (get_option('bobsnav_pos_bot') == '1') { echo " checked='checked'"; } ?> value="1">Very Bottom of Post (Below Comments)<br />
						<br />
						<?php _e('Use the checkboxes above to choose the locations of where you want your Navigation Links to be located when the individual posts output.<br /><br />You can select multiple if you want it to be made available in more than 1 position.<br /><br /><small>Please check out the site from time to time to see if there are any updates or should you want to make any suggestions or requests:<br /><a href="http://blog.reformatthis.com/plugins/bobsnav/" target="_blank">http://blog.reformatthis.com/plugins/bobsnav/</a></small>') ?></td>
					</td>
				</tr>
			</table>
			</fieldset> 
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" />
			</p>
		</form>
	</div>
	<?php 
	include("admin-footer.php");
	exit;
	}
	
	// the functions that creates the options page
	function bobsnav_addoptions()
	{
		add_options_page("Bob's Nav", "Bob's Nav", 7, __FILE__, 'bobsnav_optionspage');
	}
	
	// add the options page action
	add_action('admin_menu', 'bobsnav_addoptions');
?>