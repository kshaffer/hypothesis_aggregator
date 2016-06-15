<?php
/**
 * @package hypothesis_aggregator
 * @version 0.1
 */
/*
Plugin Name: hypothes.is aggregator
Plugin URI: https://pushpullfork.com
Description: This plugin establishes a [hypothesis] shortcode that will call the hypothes.is API and return results to embed in a page/post.
Author: Kris Shaffer
Version: 0.1
Author URI: http://kris.shaffermusic.com
*/

//require_once('vendor/autoload.php');
include('httpful.phar');

class HypothesisAPI {
	protected $baseUrl = 'https://hypothes.is/api/';
	/**
	 * Search for annotations.
	 * @param $params array See http://h.readthedocs.org/en/latest/api.html#search
	 * @param $token Optional authorization token
	 * @return array Set of matching annotations
	 */
	public function search($params) {
		$response = \Httpful\Request::get($this->baseUrl . 'search?' . http_build_query($params))
			//->addHeader('Authorization', $token?"Bearer $token":null)
			->send();
		if ($response->code != '200') throw new Exception('Unexpected service response ' . $response->code);
		return $response->body->rows;
	}
	/**
	 * Read an annotation.
	 * @param $id string ID of annotation to read
	 * @param $token string Optional authorization token
	 * @return Object See http://h.readthedocs.io/en/latest/api.html#read
	 */
	public function read($id) {
		$response = \Httpful\Request::get($this->baseUrl . 'annotations/' . urlencode($id))
			//->addHeader('Authorization', $token?"Bearer $token":null)
			->send();
		if ($response->code != '200') throw new Exception('Unexpected service response ' . $response->code);
		return $response->body;
	}
}

function hypothesis_search() {

}

function hypothesis_shortcode() {
		$output = '';
		$hypothesis = new HypothesisAPI();
		$search_results = $hypothesis->search(array('user' => 'kris.shaffer'));
		foreach($search_results as $key => $value) {
			$output .= '<a href="';
			$output .= $hypothesis->read($value->id)->uri;
			$output .= '">';
			$output .= $hypothesis->read($value->id)->document->title[0];
			$output .= '</a>';
			$output .= '<br/>';

			$output .= "<br/>";
		}
		return $output;
}

function hypothesis_register_shortcode() {
    add_shortcode( 'hypothesis', 'hypothesis_shortcode' );
}

add_action( 'init', 'hypothesis_register_shortcode' );

?>
