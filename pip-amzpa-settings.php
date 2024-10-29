<?php

class PipAmzpaSettings
{

	public $helper;

    function __construct(
		$helper
	){
		$this->helper = $helper;
	}

	function init() {
		add_action( 'admin_init', [$this, 'pip_amzpa_settings_init'] );
		add_action( 'admin_menu', [$this, 'pip_amzpa_options_page'] );
	}

	// TODO: make one function for the description and echo-out for all of the `form_field_*` functions to run through
	function form_field_checkbox( $args ) {
		$value = get_option( $this->helper->getOptionName( $args['name'] ) );
		$html  = '';
		$html .= '<input
					id="' . esc_attr( $args['id'] ) . '" 
					name="' . $this->helper->getOptionName( $args['name'] ) . '" 
					class="' . ( isset($args['class'] ) ? $args['class'] : '' ) . '" 
					type="checkbox" '.
					( $value == 'on' ? ' checked="checked" ' : '' ).
					'/>';
		if( !empty( $args['description'] ) ) {
			$html .= '<p class="wndspan">' . wp_kses( $args['description'], ['b' => [], 'br' => [], 'a' => ['href' => [], 'target' => []]] ) . '</p>';
		}
		echo wp_kses(
			$html,
			[
				'input' => ['id' => [],'name' => [],'class' => [],'type' => [],'checked' => []],
				'p' => [
					'class' => []
				],
				'a' => [
					'href' => [],
					'target' => []
				],
				'b' => [],
				'br' => []
			]
		);
	}

	function form_field_dropdown( $args ) {
		$valueCurrent = get_option( $this->helper->getOptionName( $args['name'] ) );
		$html  = '';
		$html .= '<select';
		$html .= ' id="' . esc_attr( $args['id'] ).'"';
		$html .= ' name="' . $this->helper->getOptionName( $args['name'] ) . '"';
		$html .= ' class="' . ( isset($args['class'] ) ? $args['class'] : '' ) . '"';
		$html .= '>';
		foreach( $args['options'] AS $value => $label ) {
			$html .= '<option
						value="'.$value.'"'.
						selected( $value, $valueCurrent, false )
					 .'>'.
						esc_html( $label, 'pip-amazon-product-advertising' ).
					 '</option>';
		}
		$html .= '</select>';
		if( !empty( $args['description'] ) ) {
			$html .= '<p class="wndspan">' . wp_kses( $args['description'], ['b' => [], 'br' => [], 'a' => ['href' => [], 'target' => []]] ) . '</p>';
		}
		echo wp_kses(
			$html,
			[
				'select' => ['id' => [],'name' => [],'class' => []],
				'option' => ['value' => [],'selected' => []],
				'p' => [
					'class' => []
				],
				'a' => [
					'href' => [],
					'target' => []
				],
				'b' => [],
				'br' => []
			]
		);
	}

	function form_field_text( $args ) {
		$value = get_option( $this->helper->getOptionName( $args['name'] ) );
		$html  = '';
		$html .= '<input';
		$html .= ' id="' . esc_attr( $args['id'] ) . '"';
		$html .= ' name="' . $this->helper->getOptionName( $args['name'] ) . '"';
		$html .= ' class="' . ( isset($args['class'] ) ? $args['class'] : '' ) . '"';
		$html .= ' type="' . $args['subtype'] . '"';
		$html .= ' value="' . $value . '"';
		$html .= '/>';
		if( !empty( $args['description'] ) ) {
			$html .= '<p class="wndspan">' . wp_kses( $args['description'], ['b' => [], 'br' => [], 'a' => ['href' => [], 'target' => []]] ) . '</p>';
		}
		echo wp_kses(
			$html,
			[
				'input' => [
					'id' => [],
					'name' => [],
					'class' => [],
					'type' => [],
					'value' => []
				],
				'p' => [
					'class' => []
				],
				'a' => [
					'href' => [],
					'target' => []
				],
				'b' => [],
				'br' => []
			]
		);
	}

	function form_field_textarea( $args ) {
		$value = get_option( $this->helper->getOptionName( $args['name'] ) );
		$html  = '';
		$html .= '<textarea';
		$html .= ' id="' . esc_attr( $args['id'] ) . '"';
		$html .= ' name="' . $this->helper->getOptionName( $args['name'] ) . '"';
		$html .= ' class="' . ( isset($args['class'] ) ? $args['class'] : '' )  .'"';
		$html .= ' rows="' . ( isset($args['rows'] ) ? $args['rows'] : '4' ) . '"';
		$html .= ' cols="' . ( isset($args['cols'] ) ? $args['cols'] : '50' ) . '"';
		$html .= '>';
		$html .= $value;
		$html .= '</textarea>';
		if( !empty( $args['description'] ) ) {
			$html .= '<p class="wndspan">' . wp_kses( $args['description'], ['b' => [], 'br' => [], 'a' => ['href' => [], 'target' => []]] ) . '</p>';
		}
		echo wp_kses(
			$html,
			[
				'textarea' => ['id' => [],'name' => [],'class' => [],'rows' => [],'cols' => []],
				'p' => [
					'class' => []
				],
				'a' => [
					'href' => [],
					'target' => []
				],
				'b' => [],
				'br' => []
			]
		);
	}

	/**
	 * custom option and settings
	 */
	function pip_amzpa_settings_init() {
		$this->pip_amzpa_settings_init_auth();
		$this->pip_amzpa_settings_init_ads_auto();
		$this->pip_amzpa_settings_init_disclaimer_auto();
		$this->pip_amzpa_settings_init_disclaimer_footer();
	}

	function pip_amzpa_settings_init_auth() {
		add_settings_section(
			PipAmzpaHelper::OPTION_SECTION_AUTH_AMZ,
			__( 'Amazon Credentials', 'pip-amazon-product-advertising' ),
			[$this, 'pip_amzpa_section_amz_auth_callback'],
			PipAmzpa::PLUGIN_NAMESPACE
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_MARKETPLACE ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTH_MARKETPLACE,
			esc_attr__( 'Marketplace', 'pip-amazon-product-advertising' ),
			[$this, 'form_field_dropdown'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTH_AMZ,
			[
				'id'                    => PipAmzpaHelper::OPTION_AUTH_MARKETPLACE,
				'name'                  => PipAmzpaHelper::OPTION_AUTH_MARKETPLACE,
				'class'                 => 'pip_amzpa_row',
				'options'               => [
					"" => "-- Select a Marketplace --",
					"www.amazon.com" => "www.amazon.com",
					"www.amazon.ca" => "www.amazon.ca",
					"www.amazon.com.mx" => "www.amazon.com.mx",
					"www.amazon.com.br" => "www.amazon.com.br",
					"www.amazon.co.uk" => "www.amazon.co.uk",
					"www.amazon.fr" => "www.amazon.fr",
					"www.amazon.de" => "www.amazon.de",
					"www.amazon.es" => "www.amazon.es",
					"www.amazon.in" => "www.amazon.in",
					"www.amazon.it" => "www.amazon.it",
					"www.amazon.ae" => "www.amazon.ae",
					"www.amazon.sa" => "www.amazon.sa",
					"www.amazon.com.tr" => "www.amazon.com.tr",
					"www.amazon.nl" => "www.amazon.nl",
					"www.amazon.se" => "www.amazon.se",
					"www.amazon.pl" => "www.amazon.pl",
					"www.amazon.eg" => "www.amazon.eg",
					"www.amazon.com.be" => "www.amazon.com.be",
					"www.amazon.co.jp" => "www.amazon.co.jp",
					"www.amazon.com.au" => "www.amazon.com.au",
					"www.amazon.sg" => "www.amazon.sg",
				]
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_ID_ACCOUNT ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTH_ID_ACCOUNT,
			esc_attr__('Partner Tag', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTH_AMZ,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_AUTH_ID_ACCOUNT,
				'name'             => PipAmzpaHelper::OPTION_AUTH_ID_ACCOUNT,
				'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'description'      => 'Tag that uniquely identifies you as a Partner. In case of Associates, enter your Store Id.'
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_KEY_ACCESS ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTH_KEY_ACCESS,
			esc_attr__('Access Key', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTH_AMZ,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_AUTH_KEY_ACCESS,
				'name'             => PipAmzpaHelper::OPTION_AUTH_KEY_ACCESS,
				'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'description'      => 'Your Access Key which uniquely identifies you.'
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_KEY_SECRET ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTH_KEY_SECRET,
			esc_attr__('Secret Key', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTH_AMZ,
			[
				'type'             => 'input',
				'subtype'          => 'password',
				'id'               => PipAmzpaHelper::OPTION_AUTH_KEY_SECRET,
				'name'             => PipAmzpaHelper::OPTION_AUTH_KEY_SECRET,
				'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'description'      => 'A key that is used in conjunction with the Access Key to cryptographically sign an API request. To retrieve your Access Key or Secret Key, refer to <a href="https://webservices.amazon.com/paapi5/documentation/register-for-pa-api.html"  target="_blank">Becoming Product Advertising API Developer</a>.'
			]
		);
	}

	function pip_amzpa_settings_init_disclaimer_auto() {
		add_settings_section(
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_AUTO,
			__( 'Auto Disclaimer', 'pip-amazon-product-advertising' ),
			[$this, 'pip_amzpa_section_amz_disclaimer_auto_callback'],
			PipAmzpa::PLUGIN_NAMESPACE
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_ENABLED ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_ENABLED,
			esc_attr__('Enable Auto Disclaimer on Post Pages', 'pip-amazon-product-advertising'),
			[$this, 'form_field_checkbox'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_AUTO,
			[ 
				'type'         => 'checkbox',
				'name'         => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_ENABLED,
				'id'           => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_ENABLED,
				'description'  => esc_attr__( 'Check to enable Disclaimer on posts with Amazon affilate links.', 'pip-amazon-product-advertising' ),
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT,
			esc_attr__('Section Heading', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_AUTO,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT,
				'name'             => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT,
				'required'         => 'false',
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT ) );
		if( in_array( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT ) ), [false, null] ) ) {
			update_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT ), 'This post may contain affiliate links, which means I\'ll receive a commission if you purchase through my links, at no extra cost to you.' );
		}
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT,
			esc_attr__('Disclaimer Text', 'pip-amazon-product-advertising'),
			[$this, 'form_field_textarea'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_AUTO,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT,
				'name'             => PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT,
				'required'         => 'false',
			]
		);
	}

	function pip_amzpa_settings_init_disclaimer_footer() {
		add_settings_section(
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_FOOTER,
			esc_attr__( 'Footer Disclaimer', 'pip-amazon-product-advertising' ),
			[$this, 'pip_amzpa_section_amz_disclaimer_footer_callback'],
			PipAmzpa::PLUGIN_NAMESPACE
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_ENABLED ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_ENABLED,
			esc_attr__('Enable Footer Disclaimer on all frontend pages', 'pip-amazon-product-advertising'),
			[$this, 'form_field_checkbox'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_FOOTER,
			[ 
				'type'         => 'checkbox',
				'name'         => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_ENABLED,
				'id'           => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_ENABLED,
				'description'  => esc_attr__( 'Check to enable Disclaimer in the foter of all pages on the frontend of the website.', 'pip-amazon-product-advertising' ),
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT,
			esc_attr__('Section Heading', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_FOOTER,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT,
				'name'             => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT,
				'required'         => 'false',
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT ) );
		if( in_array( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT ) ), [false, null] ) ) {
			update_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT ), 'This website is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com' );
		}
		add_settings_field(
			PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT,
			esc_attr__('Disclaimer Text', 'pip-amazon-product-advertising'),
			[$this, 'form_field_textarea'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_DISCLAIMER_FOOTER,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT,
				'name'             => PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT,
				'required'         => 'false',
			]
		);
	}

	function pip_amzpa_settings_init_ads_auto() {
		add_settings_section(
			PipAmzpaHelper::OPTION_SECTION_AUTO,
			__( 'Auto Ads on Posts', 'pip-amazon-product-advertising' ),
			[$this, 'pip_amzpa_section_amz_ads_auto_callback'],
			PipAmzpa::PLUGIN_NAMESPACE
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_ENABLED ) );
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTO_ENABLED,
			esc_attr__('Enable Amazon Product Advertising Auto Post Ads', 'pip-amazon-product-advertising'),
			[$this, 'form_field_checkbox'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTO,
			[ 
				'type'         => 'checkbox',
				'name'         => PipAmzpaHelper::OPTION_AUTO_ENABLED,
				'id'           => PipAmzpaHelper::OPTION_AUTO_ENABLED,
				'description'  => esc_attr__( 'Check to enable Amazon Product Merchandising Ads.', 'pip-amazon-product-advertising' ),
			]
		); 

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS ) );
		if( in_array( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS ) ), [false, null] ) ) {
			update_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS ), '4' );
		}
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS,
			esc_attr__('Number of Items to Fetch from Amazon', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTO,
			[
				'type'             => 'input',
				'subtype'          => 'number',
				'id'               => PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS,
				'name'             => PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS,
				'required'         => 'true',
				'description'      => 'How many items should be pulled from the Amazon search.  If not set, defaults to 4.'
			]
		);

		register_setting( PipAmzpa::PLUGIN_NAMESPACE, $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT ) );
		if( in_array( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT ) ), [false, null] ) ) {
			update_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT ), 'Products Related to this Post from Amazon' );
		}
		add_settings_field(
			PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT,
			esc_attr__('Section Heading', 'pip-amazon-product-advertising'),
			[$this, 'form_field_text'],
			PipAmzpa::PLUGIN_NAMESPACE,
			PipAmzpaHelper::OPTION_SECTION_AUTO,
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT,
				'name'             => PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT,
				'required'         => 'false',
			]
		);
	}

	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	function pip_amzpa_section_amz_auth_callback( $args ) {
		?>
		
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses( 'Configure the Amazon Product Advertising authentication settings.<br>To register for the Amazon Product Advertising program and/or obtain <b>Access</b> and <b>Secret</b> Keys, reference this <a href="https://webservices.amazon.com/paapi5/documentation/register-for-pa-api.html" target="_blank">Amazon Product Advertising API Documentation</a>', ['b' => [],'br' => [],'a' => ['href' => [], 'target' => []]] ); ?></p>
		<?php
	}

	function pip_amzpa_section_amz_disclaimer_auto_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses( '<b>Auto Disclaimer</b> will show a disclaimer message on any posts containing a link to a <b>amzn.to</b> URL.  This disclaimer is required per the Amazon Code of Concuct and Federal Trade Commision (FTC) regulations.  [<a href="https://affiliate-program.amazon.com/help/node/topic/GHQNZAU6669EZS98" target="_blank">More Information</a>]', ['b' => [],'a' => ['href' => [], 'target' => []]] ); ?></p>
		<?php
	}

	function pip_amzpa_section_amz_disclaimer_footer_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses( '<b>Footer Disclaimer</b> will show a disclaimer message in the footer across the entire frontend site.', ['b' => []] ); ?></p>
		<?php
	}

	function pip_amzpa_section_amz_ads_auto_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses( '<b>Auto Ads</b> will show on any posts containing a link to a <b>amzn.to</b> URL.  The <b>Auto Ads</b> help to suggest products related to the post, thus increasing Amazon Affiliate ad revenue.<br><br><b>Auto Ads</b> use the <b>tags</b> assigned to the post as the search keywords.  If no <b>tags</b> are on the post, then the post title will be used as the search terms.', ['b' => [], 'br' => []] ); ?></p>
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	function pip_amzpa_options_page() {
		add_menu_page(
			'Amazon Product Advertising Options',
			'Amazon Product Advertising',
			'manage_options',
			PipAmzpa::PLUGIN_NAMESPACE,
			[$this, 'pip_amzpa_options_page_html']
		);
	}

	/**
	 * Top level menu callback function
	 */
	function pip_amzpa_options_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// check if the user have submitted the settings
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'pip_amzpa_messages', 'pip_amzpa_message', __( 'Settings Saved', 'pip-amazon-product-advertising' ), 'updated' );
		}
		// show error/update messages
		settings_errors( 'pip_amzpa_messages' );
		echo '<div class="wrap">';
			echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
			echo '<form action="options.php" method="post">';
				settings_fields( PipAmzpa::PLUGIN_NAMESPACE );
				do_settings_sections( PipAmzpa::PLUGIN_NAMESPACE );
				submit_button( 'Save Settings' );
			echo '</form>';
		echo '</div>';
	}

}
