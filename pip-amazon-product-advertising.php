<?php
/*
Plugin Name:  Affiliate Product Ads for Amazon Associates
Description:  Display Amazon Product Advertising product ads easily in Wordpress
Version:      1.1.3
Author:       PromInc Productions
Author URI:   https://promincproductions.com/blog
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PipAmzpa
{

	const PLUGIN_NAMESPACE = 'pip-amazon-product-advertising';

	const REQUIRES = ['pip-amzpa-helper', 'pip-amzpa-settings', 'pip-amzpa-ads-handler', 'pip-amzpa-disclaimer-auto', 'pip-amzpa-disclaimer-footer'];

	public $helper;
	public $settings;
	public $adsHandler;
	public $disclaimerAuto;
	public $disclaimerFooter;

    function __construct(){
		$this->loadRequires();

		$this->helper           = new PipAmzpaHelper;
		$this->settings         = new PipAmzpaSettings( $this->helper );
		$this->adsHandler       = new PipAmzpaAdsHandler( $this->helper );
		$this->disclaimerAuto   = new PipAmzpaDisclaimerAuto( $this->helper );
		$this->disclaimerFooter = new PipAmzpaDisclaimerFooter( $this->helper );

		$this->init();
	}

	function loadRequires() {
		foreach( self::REQUIRES AS $require ) {
			require_once $this->getPluginDir() . $require . '.php';
		}
	}

	function init() {
		// Admin Settings
		$this->settings->init();

		// load css/js
		add_action( 'wp_enqueue_scripts', [$this, 'loadScripts' ] );

		// Ads Auto
		add_filter( 'the_content', function($content) { return $this->adsHandler->getAdsAuto($content); } );

		// Disclaimer Auto
		add_filter( 'the_content', function($content) { return $this->disclaimerAuto->getDisclaimerAuto($content); } );

		// Disclaimer Footer
		add_action( 'wp_footer', function() { $this->disclaimerFooter->setDisclaimerFooter(); } );

		// Ads by ASIN Shortcode
		add_shortcode( 'pip-amzpa-product-asin', [$this, 'pip_amzpa_shortcode_product_asin'] );
	}

	function getPluginDir() {
		return plugin_dir_path(__FILE__);
	}

	function loadScripts() {
		wp_register_style( self::PLUGIN_NAMESPACE, plugins_url( 'pip-amzpa.css', __FILE__ ) );
		wp_enqueue_style( self::PLUGIN_NAMESPACE );
	}

	// TODO: Add GA4 promotion tracking options from this module
	//  - Only track a view once scrolled into the viewport

	/**
	 * The Amazon Product ASIN shortcode.
	 *
	 * Accepts an Amazon ASIN and optional title to display a product on the page from the Amazon Products Advertising API
	 *
	 * @param array  $attributes    Shortcode attributes. Default empty.
	 * @param string $content Shortcode content. Default null.
	 * @param string $tag     Shortcode tag (name). Default empty.
	 * @return string Shortcode output.
	 */
	function pip_amzpa_shortcode_product_asin( $attributes = [], $content = null, $tag = '' ) {
		// normalize attribute keys, lowercase
		$args = array_change_key_case( (array) $attributes, CASE_LOWER );

		// override default attributes with user attributes
		$shortcodeArgs = shortcode_atts(
			array(
				'title' => '',
				'caption' => $content,
				'asin' => '',
				'show-details' => 0,
				'show-button' => 1,
				'button-text' => 'Buy on Amazon',
				'show-prime' => 0
			), $args, $tag
		);

		$amznAd = $this->adsHandler->getAdsByAsin(
			$shortcodeArgs['asin'],
			$shortcodeArgs['title'],
			$shortcodeArgs['caption'],
			$shortcodeArgs['button-text'],
			$shortcodeArgs['show-button'],
			$shortcodeArgs['show-details'],
			$shortcodeArgs['show-prime']
		);

		return print_r( $amznAd, true );
	}

}

new PipAmzpa();
