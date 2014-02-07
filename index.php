<?php
/**
 * Plugin Name: Image Importer
 * Plugin URI: http://github.com/toastedlime/image-importer
 * Description: Imports images from a WordPress XML export file. This is useful if you have a large number of images to import and your server times out while importing using the WordPress Importer plugin.
 * Version: 0.1
 * Author: Toasted Lime
 * Author URI: http://www.toastedlime.com
 * License: Apache 2.0
 */

function image_importer_scripts(){

	wp_register_script( 'image-importer-js', plugins_url( 'main.js', __FILE__ ), array( 'jquery' ) );

}

function image_importer_add_page(){
	
	add_media_page( 'Image Importer', 'Image Importer', 'manage_options','image-importer','image_importer_options_page' );
	
}

function image_importer_options_page(){

	wp_enqueue_script( 'image-importer-js' );
	
?>

<div>

<h2>Image Importer</h2>

<noscript>

	<div class="error">

		<p>Sorry, but your browser doesn't have JavaScript enabled, and this plugin requires JavaScript.</p>

		<p>Please enable JavaScript for this site to continue.</p>

	</div>

</noscript>

<div id="image-importer-init"></div>

<div id="image-importer-output"></div>

</div>

<?php
}

add_action( 'admin_enqueue_scripts', 'image_importer_scripts' );

add_action( 'admin_menu', 'image_importer_add_page' );

add_action( 'wp_ajax_image_importer_init_success', 'image_importer_init_success' );

add_action( 'wp_ajax_image_importer_init_failure', 'image_importer_init_failure' );

add_action( 'wp_ajax_image_importer_upload', 'image_importer_uploader' );

// AJAX functions are below this line.

function image_importer_init_success(){
?>
	<p>Select the WordPress eXtended RSS (WXR) file and we'll try to get the images and upload them to your blog here.</p>

	<p>Choose a WXR (.xml) file from your computer and press upload.</p>

	<p><input type="file" id="file"/></p>

	<p><button class="button">Upload</button></p>

<?php
die();}

function image_importer_init_failure(){
?>

<div class="error">
	<p>Sorry, but you're using an <strong>outdated</strong> browser that doesn't support the features required to use this plugin.</p>
	<p>Please <a href="http://www.browsehappy.com">upgrade your browser</a> in order to use this plugin.</p>
</div>

<?php
die();}

function image_importer_uploader(){

	$parameters = array(
		'url' => $_POST['url'],
		'title' => $_POST['title'],
		'link' => $_POST['link'],
		'pubDate' => $_POST['pubDate'],
		'creator' => $_POST['creator'],
		'guid' => $_POST['guid'],
		'post_id' => $_POST['post_id'],
		'post_date' => $_POST['post_date'],
		'post_date_gmt' => $_POST['post_date_gmt'],
		'comment_status' => $_POST['comment_status'],
		'ping_status' => $_POST['ping_status'],
		'post_name' => $_POST['post_name'],
		'status' => $_POST['status'],
		'post_parent' => $_POST['post_parent'],
		'menu_order' => $_POST['menu_order'],
		'post_type' => $_POST['post_type'],
		'post_password' => $_POST['post_password'],
		'is_sticky' => $_POST['is_sticky']
	);

	function upload( $image ){
		
		return wp_get_http( $image['url'] );
	}

	echo json_encode( upload( $parameters ) );

die();}