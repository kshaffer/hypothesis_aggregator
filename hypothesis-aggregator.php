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
		//if ($response->code != '200') throw new Exception('Unexpected service response ' . $response->code);
		return $response->body;
	}
}

function hypothesis_shortcode() {
		$output = '';
		$hypothesis = new HypothesisAPI();
		$search_results = $hypothesis->search(array('user' => 'kris.shaffer'));
		foreach($search_results as $key => $value) {
				// store annotation locally
				$annotation_local = $hypothesis->read($value->id);

				// title of post with link to original post
				$output .= '<a href="';
				$output .= $annotation_local->uri;
				$output .= '">';
				$output .= $annotation_local->document->title[0];
				$output .= '</a>';
				$output .= '<br/><br/>';

				// grab highlighted portion of post
				if (in_array('selector', array_keys(get_object_vars($annotation_local->target[0])))) {
						$selector = $annotation_local->target[0]->selector;
						foreach($selector as $entry => $value) {
								if (in_array('exact', array_keys(get_object_vars($value)))) {
										$target_info = $value->exact;
								} else {
										$target_info = 'No highlighted text in this annotation.';
								}
						}
				} else {
						$target_info = 'No highlighted text in this annotation.';
				}

				// highlighted portion of post as blockquote
				$output .= '<blockquote>';
				$output .= $target_info;
				$output .= '</blockquote><br/>';

				// annotation comment, with username and link to stream
				if ($annotation_local->text) {
						$output .= $annotation_local->text;
						$output .= '<br/>';

				}
				$account = $annotation_local->user;
				$output .= 'Curated by <a href="';
				$output .= "https://hypothes.is/stream?q=user:";
				list($dump1, $account_name, $dump2) = split('[:@]', $account);
				$output .= $account_name;
				$output .= '">';
				$output .= $account_name;
				$output .= '</a>.<br/>';

				$output .= "<br/><hr/><br/>";
		}
		return $output;
}

function hypothesis_register_shortcode() {
    add_shortcode( 'hypothesis', 'hypothesis_shortcode' );
}

add_action( 'init', 'hypothesis_register_shortcode' );

?>
