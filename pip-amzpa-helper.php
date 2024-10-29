<?php

class PipAmzpaHelper
{

	const OPTION_SECTION_AUTH_AMZ = PipAmzpa::PLUGIN_NAMESPACE . '_section_amz_auth';
	const OPTION_AUTH_MARKETPLACE = 'auth_marketplace';
	const OPTION_AUTH_ID_ACCOUNT  = 'auth_partner_tag';
	const OPTION_AUTH_KEY_ACCESS  = 'auth_access_key';
	const OPTION_AUTH_KEY_SECRET  = 'auth_secret_key';

	const OPTION_SECTION_DISCLAIMER_AUTO   = PipAmzpa::PLUGIN_NAMESPACE . '_section_disclaimer_auto';
	const OPTION_DISCLAIMER_AUTO_ENABLED   = 'disclaimer_auto_enabled';
	const OPTION_DISCLAIMER_AUTO_HEAD_TEXT = 'disclaimer_auto_heading_text';
	const OPTION_DISCLAIMER_AUTO_TEXT      = 'disclaimer_auto_text';

	const OPTION_SECTION_DISCLAIMER_FOOTER   = PipAmzpa::PLUGIN_NAMESPACE . '_section_disclaimer_footer';
	const OPTION_DISCLAIMER_FOOTER_ENABLED   = 'disclaimer_footer_enabled';
	const OPTION_DISCLAIMER_FOOTER_HEAD_TEXT = 'disclaimer_footer_heading_text';
	const OPTION_DISCLAIMER_FOOTER_TEXT      = 'disclaimer_footer_text';

	const OPTION_SECTION_AUTO   = PipAmzpa::PLUGIN_NAMESPACE . '_section_auto';
	const OPTION_AUTO_ENABLED   = 'auto_enabled';
	const OPTION_AUTO_NUM_ITEMS = 'auto_num_items';
	const OPTION_AUTO_HEAD_TEXT = 'auto_heading_text';

	function getOptionName( $name ) {
		if( !is_string( $name ) ) {
			return false;
		}
		return PipAmzpa::PLUGIN_NAMESPACE . '_' . esc_attr($name);
	}

	function getAmazonLinkIdentifiers() {
		$amazonLinkIdentifiers = [ "//amzn.to/" ];
		return $amazonLinkIdentifiers;
	}

	function getContentHasAmazonLinks( $content ) {
		foreach( $this->getAmazonLinkIdentifiers() AS $identifier ) {
			if( strpos( $content, $identifier ) > 0 ) {
				return true;
			}
		}
		return false;
	}

}
