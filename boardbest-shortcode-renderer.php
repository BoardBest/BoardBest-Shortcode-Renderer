<?php
/*
Plugin Name: BoardBest shordcode renderer
Description: Shordcode graphics renderer inside TinnyMCE
Version:     0.0.1
Author:      dadmor@gmail.com

Copyright © 2017-2017 FutureNet.club

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

/* -----------------------------------------------------------*/
/* Register scripts */
function BBest_shortcode_renderer_enqueue_script()
{   
	wp_enqueue_script("jquery");
 	wp_enqueue_script( 'init_shortcode_renderer', plugin_dir_url( __FILE__ ) . 'js/init.js' );
}
add_action('wp_enqueue_scripts', 'BBest_shortcode_renderer_enqueue_script');

/* -----------------------------------------------------------*/
/* Register TinnyMCE plugins */
function BBest_shortcode_renderer_add_tcustom_tinymce_plugin($plugin_array) {
	$plugin_array['shortcoderenderer'] = plugin_dir_url( __FILE__ ) . 'js/shortcode-renderer-plugin.js';
	return $plugin_array;
}
add_filter(	'mce_external_plugins', 'BBest_shortcode_renderer_add_tcustom_tinymce_plugin');

/* -----------------------------------------------------------*/
/* Add events to tinny */
function BBest_shortcode_renderer_tinymce_add_events( $initArray ) {
	$code = 'function(ed) {
			ed.onClick.add(
				function(ed, e) {
					_BBSR.tinnyClickGraber(e);
				}
			);
		}';
	$initArray['setup'] = str_replace(array("\n","\r"),'',$code);
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'BBest_shortcode_renderer_tinymce_add_events' );

/* -----------------------------------------------------------*/
/* Add css to tinny */
function BBest_shortcode_renderer_tinymce_add_css( $initArray ) {
	
	$code = str_replace(array("\n","\r"),'',file_get_contents(__DIR__.'/css/'.'mce-editor-style.css'));
	$initArray['content_style'] = $code;
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'BBest_shortcode_renderer_tinymce_add_css' );




function editor_scripts() {
	global $post;
	$content = '';
	$editor_id = 'FN_frontend_editor';
	$inner_editor_id = 'FN_inner_editor';
	$settings =  array(
		'quicktags' => false,
		'wpautop' => true,
	);	
	$edit = '';
	$edit .= '<div>';
		ob_start();	
		wp_editor( $content, $editor_id, $settings ); 
		$edit .= ob_get_clean();
	$edit .= '</div>';
	$edit .= '<button onclick="_BBSR.insertString(\'[test]\');">xxx</button>';
	echo $edit;

}
add_action( 'wp_footer', 'editor_scripts' );