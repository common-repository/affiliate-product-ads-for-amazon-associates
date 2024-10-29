<?php

class PipAmzpaDisclaimerAuto
{

	public $helper;

    function __construct(
		$helper
	){
		$this->helper = $helper;
	}

	function getDisclaimerAuto($content) {
		if( is_single() ){
			/* Disclaimer */
			if(
				   get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_ENABLED ) ) == 'on'
				&& strlen( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT ) ) ) > 0
			) {
				$applicablePost = false;
				if( !$applicablePost ) {
					$applicablePost = $this->helper->getContentHasAmazonLinks( $content );
				}
				if( $applicablePost ) {
					$content .= '<div class="pip-amzpa-disclaimer pip-amzpa-disclaimer-auto">';
						if( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT ) ) ) {
						$content .= '<div class="pip-amzpa-disclaimer-title">';
							$content .= get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_HEAD_TEXT ) );
						$content .= '</div>';
						}

						$content .= '<i>';
							$content .= get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_AUTO_TEXT ) );
						$content .= '</i>';
					$content .= '</div>';
				}
			}
		}
		return wp_kses_post( $content );
	}

}
