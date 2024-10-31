<?php
/*
Plugin Name: PowerInviter
Version: 1.0
Plugin URI: http://powerinviter.com
Description: PowerInviter represents a new generation of "Tell a friend" scripts with many additional "Pro" features and viral marketing principles
Author: PowerInviter.com
Author URI: http://powerinviter.com
*/

class PowerInviter {

	var $invm_opts;
	var $TinyMCEVersion = 200;
	var $TinyMCEVpluginname = 'PowerInviter';

	function PowerInviter() {
	
		$this->invm_opts = get_option('powerinviter_options');
		
		register_activation_hook( __FILE__, array(&$this, 'powerinviter_install')); 
		register_deactivation_hook( __FILE__, array(&$this, 'powerinviter_uninstall'));

		if ( $this->invm_opts['addmode'] == 'manual' ) {
			if ( function_exists ('add_shortcode') ) {
				add_shortcode('powerinviter', array(&$this, 'invite_shortcode'));
			}
			add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version'));
			add_action('init', array (&$this, 'addbuttons') );
		}
		else {
			add_action('the_content', array(&$this, 'powerinviter_add_to_page'));
		}

		add_action('wp_head', array(&$this, 'powerinviter_add_header_js'));
		add_action('admin_menu', array(&$this, 'powerinviter_add_options_page'));
	}
	
	function powerinviter_install () {
		
		if ( !$this->invm_opts ) {
			
			$this->invm_opts = array();
			
			$this->invm_opts['registered'] = '0';
			$this->invm_opts['userid'] = '';
			$this->invm_opts['formid'] = '';
			$this->invm_opts['place_2'] = '1';
			$this->invm_opts['place_4'] = '1';
			$this->invm_opts['v_align'] = 'bottom';
			$this->invm_opts['h_align'] = 'left';
			$this->invm_opts['addmode'] = 'auto';
			$this->invm_opts['m_top'] = '0';
			$this->invm_opts['m_bottom'] = '0';
			$this->invm_opts['m_left'] = '0';
			$this->invm_opts['m_right'] = '0';
			$this->invm_opts['css'] = 'display:inline;';
			$this->invm_opts['zindex'] = '1000';
			$this->invm_opts['recommend_button_file'] = 'recommend_16_1';
			$this->invm_opts['show_tab'] = '1';
			$this->invm_opts['tab_align'] = 'right';
			$this->invm_opts['tab_color'] = 'orange';
			$this->invm_opts['tab_offset'] = '200';

			add_option('powerinviter_options', $this->invm_opts);
		}
	}
	
	function powerinviter_uninstall () {
		
		delete_option('powerinviter_options');
	}

	function powerinviter_setup_page() {

		if ( function_exists('current_user_can') && !current_user_can('manage_options') ) die( _e('access denied', 'powerinviter'));

		if( $_POST['action'] == 'powerinviter_options_save' ) { $this->powerinviter_options_save(); }

		$current_options = $this->invm_opts;
		$registered = $current_options['registered'];
		$userid = $current_options['userid'];
		$formid = $current_options['formid'];
		$place_1 = $current_options['place_1'];
		$place_2 = $current_options['place_2'];
		$place_4 = $current_options['place_4'];
		$v_align = $current_options['v_align'];
		$h_align = $current_options['h_align'];
		$addmode = $current_options['addmode'];
		$m_top = $current_options['m_top'];
		$m_bottom = $current_options['m_bottom'];
		$m_left =$current_options['m_left'];
		$m_right = $current_options['m_right'];
		$css = $current_options['css'];
		$zindex = $current_options['zindex'];
		$recommend_button_file = $current_options['recommend_button_file'];
		$show_tab = $current_options['show_tab'];
		$tab_align = $current_options['tab_align'];
		$tab_color = $current_options['tab_color'];
		$tab_offset = $current_options['tab_offset'];

		$powerinviter_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
		$powerinviter_plugin_path = ereg_replace("/$", "", $powerinviter_plugin_path);

		if ($_POST['action']) { ?><div id="message" class="updated fade"><p><strong>Your settings has been updated!</strong></p></div><?php } ?>

		<body onLoad="powerinviter_getcode()">

		<script language="JavaScript">
		function powerinviter_getcode()
		{
		var f=document.forms.powerinviter_setup;
		//if(f.registered[1].checked){document.getElementById('powerinviter_accountinfo').style.display="block"}
		//else{document.getElementById('powerinviter_accountinfo').style.display="none"}

		var powerinviter_prop
		var reg=/Internet Explorer/
		var result=reg.test(navigator.appName)?powerinviter_prop='block':powerinviter_prop='table-row'

		if(f.show_tab.checked){
		document.getElementById('powerinviter_tab_align').style.display=powerinviter_prop
		document.getElementById('powerinviter_tab_color').style.display=powerinviter_prop
		document.getElementById('powerinviter_tab_offset').style.display=powerinviter_prop
		}
		else{
		document.getElementById('powerinviter_tab_align').style.display="none"
		document.getElementById('powerinviter_tab_color').style.display="none"
		document.getElementById('powerinviter_tab_offset').style.display="none"
		}
		}
		</script>

		<div class="wrap" id="powerinviter_setup">

		<h2>PowerInviter - Setup Options</h2>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" name="powerinviter_setup" id="powerinviter_setup">
		<fieldset>

		<table width="620" border="1" cellspacing="0" cellpadding="5" bordercolor="#3384B2" class="font" style="font-family: Arial; font-size: 10pt;padding:20px;">

		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Username (login) <a href="http://static.powerinviter.com/help/account.html" target="_blank">
		<img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a>
		</td>
		<td><input type="text" name="userid" value="<?php echo ($userid); ;?>"></td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Form ID <a href="http://static.powerinviter.com/help/form_id.html" target="_blank">
		<img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a>
		</td>
		<td><input type="text" name="formid" value="<?php echo ($formid); ;?>"></td>
		</tr>

		<tr><td colspan="2">&nbsp;</td></tr>

		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Display the "Tell a friend" button</td>
		<td>
		<input type="checkbox" name="place_1" value="1" <?php echo ($place_1 == '1')?' checked="checked"':''; ;?> > Home Page<br>
		<input type="checkbox" name="place_2" value="1" <?php echo ($place_2 == '1')?' checked="checked"':''; ;?> > Posts<br>
		<input type="checkbox" name="place_4" value="1" <?php echo ($place_4 == '1')?' checked="checked"':''; ;?> > Categories<br><br>
		</td>
		</tr>

		<tr bgcolor="#E9E9E9">
		<td width="200">Adding mode <a href="http://static.powerinviter.com/help/addmode.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td>
		<select name="addmode" style="width:300px">
		<option value="auto" <?php if ($addmode === "auto") echo 'selected="selected"';?> >Automatic</option>
		<option value="manual" <?php if ($addmode === "manual") echo 'selected="selected"';?> >Manual</option>
		</select>
		</td>
		</tr>

		<tr bgcolor="#E9E9E9">
		<td width="200">Location</td>
		<td>
		<select name="v_align" style="width:300px">
		<option value="top" <?php if ($v_align === "top") echo 'selected="selected"';?> >Top</option>
		<option value="bottom" <?php if ($v_align === "bottom") echo 'selected="selected"';?> >Bottom</option>
		</select>
		</td>
		</tr>

		<tr bgcolor="#E9E9E9">
		<td width="200">Align</td>
		<td>
		<select name="h_align" style="width:300px">
		<option value="left" <?php if ($h_align === "left") echo 'selected="selected"';?> >Left</option>
		<option value="right" <?php if ($h_align === "right") echo 'selected="selected"';?> >Right</option>
		<option value="none" <?php if ($h_align === "none") echo 'selected="selected"';?> >New Line</option>
		</select>
		</td>
		</tr>

		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Margin Top <a href="http://static.powerinviter.com/help/button_margin.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="m_top" value="<?php echo ($m_top); ;?>" size="5"> px</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Margin Bottom <a href="http://static.powerinviter.com/help/button_margin.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="m_bottom" value="<?php echo ($m_bottom); ;?>" size="5"> px</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Margin Left <a href="http://static.powerinviter.com/help/button_margin.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="m_left" value="<?php echo ($m_left); ;?>" size="5"> px</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Margin Right <a href="http://static.powerinviter.com/help/button_margin.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="m_right" value="<?php echo ($m_right); ;?>" size="5"> px</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">Layer Visibility <a href="http://static.powerinviter.com/help/zindex.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="zindex" value="<?php echo ($zindex); ;?>" size="5"></td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td width="200">CSS <a href="http://static.powerinviter.com/help/add_css.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><textarea cols="28" rows="2" name="css" style="font-size:9pt; width:300px;"><? echo $css; ?></textarea></td>
		</tr>

		<tr bgcolor="#E9E9E9">
		<td colspan="2">Button image</td>
		</tr>
		<tr bgcolor="#E9E9E9">
		<td align="right" valign="bottom"><img src="<? echo $powerinviter_plugin_path; ?>/recommend_16_1.gif" width="130" height="16" border="0">&nbsp;&nbsp;</td>
		<td height="30"><input type="radio" name="recommend_button_file" value="recommend_16_1" <?php echo ($recommend_button_file == 'recommend_16_1')?' checked="checked"':''; ;?> ></td>
		</tr>
		<tr bgcolor="#E9E9E9">
		<td align="right"><img src="<? echo $powerinviter_plugin_path; ?>/recommend_26_2.gif" width="130" height="26" border="0">&nbsp;&nbsp;</td>
		<td height="30"><input type="radio" name="recommend_button_file" value="recommend_26_2" <?php echo ($recommend_button_file == 'recommend_26_2')?' checked="checked"':''; ;?> ></td>
		</tr>
		<tr bgcolor="#E9E9E9">
		<td align="right"><img src="<? echo $powerinviter_plugin_path; ?>/recommend_26_1.gif" width="26" height="26" border="0">&nbsp;&nbsp;</td>
		<td height="30"><input type="radio" name="recommend_button_file" value="recommend_26_1" <?php echo ($recommend_button_file == 'recommend_26_1')?' checked="checked"':''; ;?> ></td>
		</tr>

		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top" bgcolor="#E9E9E9">
		<td colspan="2">Display the "Tell a friend" Tab <a href="http://static.powerinviter.com/help/recommend_tab.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a>
		<input style="margin-left:100px;" type="checkbox" name="show_tab" value="1" onClick="powerinviter_getcode(this)" <?php echo ($show_tab == '1')?' checked="checked"':''; ;?> >
		</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top" bgcolor="#E9E9E9" id="powerinviter_tab_align" style="display:none">
		<td width="200">Location</td>
		<td>
		<select name="tab_align" style="width:300px">
		<option value="left" <?php if ($tab_align === "left") echo 'selected="selected"';?> >Left</option>
		<option value="right" <?php if ($tab_align === "right") echo 'selected="selected"';?> >Right</option>
		<option value="top" <?php if ($tab_align === "top") echo 'selected="selected"';?> >Top</option>
		<option value="bottom" <?php if ($tab_align === "bottom") echo 'selected="selected"';?> >Bottom</option>
		</select>
		</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9" id="powerinviter_tab_color" style="display:none">
		<td width="200">Color</td>
		<td>
		<select name="tab_color" style="width:300px">
		<option value="orange" <?php if ($tab_color === "orange") echo 'selected="selected"';?> >Orange</option>
		<option value="red" <?php if ($tab_color === "red") echo 'selected="selected"';?> >Red</option>
		<option value="green" <?php if ($tab_color === "green") echo 'selected="selected"';?> >Green</option>
		<option value="blue" <?php if ($tab_color === "blue") echo 'selected="selected"';?> >Blue</option>
		</select>
		</td>
		</tr>
		<tr valign="top" bgcolor="#E9E9E9" id="powerinviter_tab_offset" style="display:none">
		<td width="200">Offset <a href="http://static.powerinviter.com/help/tab_offset.html" target="_blank"><img src="<? echo $powerinviter_plugin_path; ?>/help.gif" alt="help" border="0"></a></td>
		<td><input type="text" name="tab_offset" value="<? echo $tab_offset; ?>" size="5"> px</td>
		</tr>
		</table>
		<input type="hidden" name="action" value="powerinviter_options_save">
		</fieldset>
		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Settings!"></p>
		</form>
        </div>
		<?php
	}

	function powerinviter_add_options_page() {

		if (function_exists('add_options_page')) {
			add_options_page('PowerInviter - Tell a Friend Tool', 'PowerInviter - Tell a Friend Tool', 10, __FILE__, array(&$this, 'powerinviter_setup_page'));
		}
	}

	function powerinviter_add_header_js($str) {
		$current_options = $this->invm_opts;

		$registered = $current_options['registered'];
		$userid = $current_options['userid'];
		$formid = $current_options['formid'];
		$show_tab = $current_options['show_tab'];
		$tab_align = $current_options['tab_align'];
		$tab_color = $current_options['tab_color'];
		$tab_offset = $current_options['tab_offset'];
		$zindex = $current_options['zindex'];

		//if($registered == 0) { $userid = 'auto'; $formid = 1; }

		$powerinviter_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
		$powerinviter_plugin_path = ereg_replace("/$", "", $powerinviter_plugin_path);

		$powerinviter_defaults = "
		<script type=\"text/javascript\">
		powerinviter_user='$userid'
		powerinviter_form='$formid'
		powerinviter_zindex='$zindex'
		powerinviter_display_tab='$show_tab'
		powerinviter_tab_orientation='$tab_align'
		powerinviter_tab_color='$tab_color'
		powerinviter_tab_offset='$tab_offset'
		powerinviter_imgpath='$powerinviter_plugin_path'
		powerinviter_engine='wp'
		</script>
		<script src=\"$powerinviter_plugin_path/widget.js\" type=\"text/javascript\"></script>
		";

		echo $powerinviter_defaults;
		return false;
	}

	function powerinviter_add_to_page($postval) {
		global $post;

		$current_options = $this->invm_opts;
		$v_align = $current_options['v_align'];
		$place_1 = $current_options['place_1'];
		$place_2 = $current_options['place_2'];
		$place_4 = $current_options['place_4'];

		$powerinviter_recommend_button = $this->invite_shortcode(0);

		if	((is_home() && $place_1) || (is_front_page() && $place_1) || (is_single() && $place_2) || (is_page() && $place_4)) {
			if( $v_align == 'top' ) {
				$postval = $powerinviter_recommend_button . $postval;
			}
			else{
				$postval .= $powerinviter_recommend_button;
			}
			$postval = str_replace('[powerinviter]', '', $postval);
		}

		return $postval;
	}

	function invite_shortcode ($atts, $content = null) {

		$current_options = $this->invm_opts;
		$userid = $current_options['userid'];
		$formid = $current_options['formid'];
		$v_align = $current_options['v_align'];
		$h_align = $current_options['h_align'];
		$m_top = $current_options['m_top'];
		$m_bottom = $current_options['m_bottom'];
		$m_left = $current_options['m_left'];
		$m_right = $current_options['m_right'];
		$css = $current_options['css'];
		$recommend_button_file = $current_options['recommend_button_file'];

		$style = 'style="border-style:none;border-width:0px;margin-top:' . $m_top . 'px;margin-bottom:' . $m_bottom . 'px;margin-left:' . $m_left . 'px;margin-right:' . $m_right . 'px;';
		if( $h_align == 'none' ) { $style .= 'float:none;'; }
		else { $style .= ($h_align == 'left')?'float:left;':'float:right;'; }
		$style .= $css . '"';

		$this_url = get_permalink($post->ID);
		$this_url = esc_js($this_url);

		$this_title = get_the_title($post->ID);
		$this_title = preg_replace("/[\r\t]/", "", $this_title);
		$this_title = preg_replace("/\n/", " ", $this_title);
		$this_title = preg_replace("/\'/", "&rsquo;", $this_title);
		$this_title = preg_replace("/\"/", "&quot;", $this_title);
		$this_title = esc_js($this_title);

		$this_description = get_the_content();
		$this_description = strip_shortcodes($this_description);
		$this_description = strip_tags($this_description);
		$this_description = preg_replace("/[\r\t]/", "", $this_description);
		$this_description = preg_replace("/\n/", " ", $this_description);
		$this_description = preg_replace("/\'/", "&rsquo;", $this_description);
		$this_description = preg_replace("/\"/", "&quot;", $this_description);
		$this_description = substr($this_description, 0, 300);
		$this_description = esc_js($this_description);

		$powerinviter_plugin_path = WP_PLUGIN_URL. '/'. str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
		$powerinviter_plugin_path = ereg_replace("/$", "", $powerinviter_plugin_path);

		$powerinviter_recommend_button = "<div $style><a href=\"http://powerinviter.com/cgi-bin/invite.pl?uid=$userid&fid=$formid&url=$this_url&title=$this_title&description=$this_description\" onclick=\"PowerInviter.ShowInviteForm({url:'$this_url',title:'$this_title',description:'$this_description'});return false;\"><img src=\"$powerinviter_plugin_path/$recommend_button_file.gif\" border=\"0\" style=\"border-style:none;border-width:0px;margin:0;padding:0\" alt=\"Tell a Friend and Get a Gift!\" title=\"Tell a Friend and Get a Gift!\"></a></div>";

		$powerinviter_recommend_button = str_replace('<br />','',$powerinviter_recommend_button);

		return $powerinviter_recommend_button;
	}

	function powerinviter_options_save() {

		if (function_exists('current_user_can') && !current_user_can('manage_options')) die( _e('access denied', 'powerinviter'));

		$this->invm_opts['registered'] = $_POST['registered'];
		$this->invm_opts['userid'] = $_POST['userid'];
		$this->invm_opts['formid'] = $_POST['formid'];
		$this->invm_opts['place_1'] = $_POST['place_1'];
		$this->invm_opts['place_2'] = $_POST['place_2'];
		$this->invm_opts['place_4'] = $_POST['place_4'];
		$this->invm_opts['v_align'] = $_POST['v_align'];
		$this->invm_opts['h_align'] = $_POST['h_align'];
		$this->invm_opts['addmode'] = $_POST['addmode'];
		$this->invm_opts['m_top'] = $_POST['m_top'];
		$this->invm_opts['m_bottom'] = $_POST['m_bottom'];
		$this->invm_opts['m_left'] = $_POST['m_left'];
		$this->invm_opts['m_right'] = $_POST['m_right'];
		$this->invm_opts['css'] = $_POST['css'];
		$this->invm_opts['zindex'] = $_POST['zindex'];
		$this->invm_opts['recommend_button_file'] = $_POST['recommend_button_file'];
		$this->invm_opts['show_tab'] = $_POST['show_tab'];
		$this->invm_opts['tab_align'] = $_POST['tab_align'];
		$this->invm_opts['tab_color'] = $_POST['tab_color'];
		$this->invm_opts['tab_offset'] = $_POST['tab_offset'];

		$this->invm_opts['m_top'] = preg_replace("/[^\d]/", "", $this->invm_opts['m_top']);
		$this->invm_opts['m_bottom'] = preg_replace("/[^\d]/", "", $this->invm_opts['m_bottom']);
		$this->invm_opts['m_left'] = preg_replace("/[^\d]/", "", $this->invm_opts['m_left']);
		$this->invm_opts['m_right'] = preg_replace("/[^\d]/", "", $this->invm_opts['m_right']);
		$this->invm_opts['css'] = preg_replace("/[\n\r\t]/", "", $this->invm_opts['css']);
		$this->invm_opts['zindex'] = preg_replace("/[^\d]/", "", $this->invm_opts['zindex']);
		$this->invm_opts['tab_offset'] = preg_replace("/[^\d]/", "", $this->invm_opts['tab_offset']);

		if($this->invm_opts['zindex'] == ""){$this->invm_opts['zindex'] = 1000;}
		if($this->invm_opts['m_top'] == ""){$this->invm_opts['m_top'] = 0;}
		if($this->invm_opts['m_bottom'] == ""){$this->invm_opts['m_bottom'] = 0;}
		if($this->invm_opts['m_left'] == ""){$this->invm_opts['m_left'] = 0;}
		if($this->invm_opts['m_right'] == ""){$this->invm_opts['m_right'] = 0;}
		if($this->invm_opts['tab_offset'] == ""){$this->invm_opts['tab_offset'] = 200;}

		update_option('powerinviter_options', $this->invm_opts);

		$options_saved=true;
	}
	
	function addbuttons() {
	
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) 
			return;

		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter("mce_external_plugins", array (&$this, 'add_tinymce_plugin' ), 5);
			add_filter('mce_buttons_3', array (&$this, 'register_button' ), 5);
		}
	}
	
	function register_button($buttons) {
		array_push($buttons, '', 'powerinviter' );
		return $buttons;
	}
	
	function add_tinymce_plugin($plugin_array) {    
		$plugin_array['PowerInviter'] = get_option('siteurl') . '/wp-content/plugins/powerinviter/editor_plugin.js';
		return $plugin_array;
	}
	
	function change_tinymce_version($version) {
		$version = $version + $this->TinyMCEVersion;
		return $version;
	}
}

$INVM = new PowerInviter();
?>