<?php
/**
 * Plugin Name: Facebook Open Graph Scraper.
 * Plugin URI: http://angrycreative.se
 * Description: On save post, send post url to facebook and re scrape open graph information.
 * Version: 1.0
 * Author: viktorfroberg
 * Author URI: http://angrycreative.se
 * License: GPLv2
 */


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 *	On save post, update facebook post cache
 */
function fogs_post_scraper($post_id, $post) {
	/**
	 * Filter what post types should be re-scraped
	 * @param post_types array
	 */
	$post_types = apply_filters( 'fogs_post_types', get_post_types());

	if(in_array($post->post_type, $post_types)){
		$url = get_permalink($post_id);
		$req = new HttpRequest('https://graph.facebook.com/', HttpRequest::METH_POST);
		$req->addPostFields(array('id' => $url, 'scrape' => 'true'));
		try {
		    echo $req->send()->getBody();
		} catch (HttpException $exception) {
		    echo $exception;
		}
	}

}
add_action( 'save_post', 'fogs_post_scraper', 10, 2);