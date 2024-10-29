<?php

class PipAmzpaDisclaimerFooter
{

	public $helper;

    function __construct(
		$helper
	){
		$this->helper = $helper;
	}

	function setDisclaimerFooter() {
		/* Disclaimer */
		if(
			   get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_ENABLED ) ) == 'on'
			&& strlen( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT ) ) ) > 0
		) {
			$outputHtml = '';
			$outputHtml .= '<div class="pip-amzpa-disclaimer pip-amzpa-disclaimer-footer">';
				if( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT ) ) ) {
				$outputHtml .= '<div class="pip-amzpa-disclaimer-title">';
					$outputHtml .= get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_HEAD_TEXT ) );
				$outputHtml .= '</div>';
				}

				$outputHtml .= '<i>';
					$outputHtml .= get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_DISCLAIMER_FOOTER_TEXT ) );
				$outputHtml .= '</i>';
			$outputHtml .= '</div>';
			echo wp_kses_post( $outputHtml );
		}
	}

}
