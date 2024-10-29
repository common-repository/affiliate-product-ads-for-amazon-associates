<?php

class PipAmzpaAdsHandler
{

	const AMZN_API_METHOD_GETITEMS = 'GetItems';
	const AMZN_API_METHOD_SEARCHITEMS = 'SearchItems';

	public $helper;

    function __construct(
		$helper
	){
		$this->helper = $helper;
	}

	function callAmazonApi( $method, $searchString = '' ) {

		if( !in_array( $method, [self::AMZN_API_METHOD_GETITEMS,self::AMZN_API_METHOD_SEARCHITEMS] ) ) {
			// not an allowed method
			return false;
		}

		if( $method == self::AMZN_API_METHOD_GETITEMS ) {
			$responseResultKey = 'ItemsResult';
		} else  if( $method == self::AMZN_API_METHOD_SEARCHITEMS ) {
			$responseResultKey = 'SearchResult';
		} else {
			return false;
		}
	
		if( substr( trim( $searchString ), -1 ) != ',' ) {
			$searchString .= ',';
		}

		$serviceName="ProductAdvertisingAPI";
		$region    = "us-east-1";
		$accessKey = get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_KEY_ACCESS ) );
		$secretKey = get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_KEY_SECRET ) );
		$payload = "{"
				. $searchString
				." \"Resources\": ["
				."  \"CustomerReviews.StarRating\","
				."  \"Images.Primary.Small\","
				."  \"Images.Primary.Medium\","
				."  \"Images.Primary.Large\","
				."  \"ItemInfo.TechnicalInfo\","
				."  \"ItemInfo.Title\","
				."  \"ItemInfo.Features\","
				."  \"Offers.Listings.Price\","
				."  \"Offers.Listings.Promotions\","
				."  \"Offers.Listings.DeliveryInfo.IsPrimeEligible\""
				." ],"
				." \"ItemCount\": ".get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_NUM_ITEMS ) ).","
				." \"PartnerTag\": \"".get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_ID_ACCOUNT ) )."\","
				." \"PartnerType\": \"Associates\","
				." \"Marketplace\": \"".get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTH_MARKETPLACE ) )."\""
				."}";
		$host="webservices.amazon.com";
		$uriPath="/paapi5/" . strtolower( $method );

		require_once 'pip-amzpa-AwsV4.php';
		$awsv4 = new PipAmzpaAwsV4($accessKey, $secretKey);
		$awsv4->setRegionName($region);
		$awsv4->setServiceName($serviceName);
		$awsv4->setPath($uriPath);
		$awsv4->setPayload($payload);
		$awsv4->setRequestMethod("POST");
		$awsv4->addHeader('content-encoding', 'amz-1.0');
		$awsv4->addHeader('content-type', 'application/json; charset=utf-8');
		$awsv4->addHeader('host', $host);
		$awsv4->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $method);

		// TODO: implement transient caching https://developer.wordpress.org/plugins/http-api/#wordpress-transients
		$argsAmazonApi = [
			'headers' => $awsv4->getHeaders(),
			'body' => $payload
		];
		$responseAmazonApi = wp_remote_post( 'https://'.$host.$uriPath, $argsAmazonApi );
		$responseCodeAmazonApi = wp_remote_retrieve_response_code( $responseAmazonApi );
		if( $responseCodeAmazonApi == 200 ) {
			$responseBodyAmazonApi = wp_remote_retrieve_body( $responseAmazonApi );
			$amzJson = json_decode( $responseBodyAmazonApi );
			$items = $amzJson->$responseResultKey->Items;
			if( count( $items ) > 0 ) {
				return $items;
			}
		}

		return false;
	}

	function getAdsAuto($content) {
		if( is_single() ){
			if( get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_ENABLED ) ) == 'on' ) {
				if( $this->helper->getContentHasAmazonLinks( $content ) ) {

// TODO: use Yoast Keywords Focus as search logic (if plugin is installed)
// TODO: use whatever Rankmath has as a good identifier for search logic (if plugin is installed)
// TODO: use post excerpt as search keywords
// TODO: use post categories as search keywords
// TODO: allow user to choose prefered first method of auto search logic (keywords, tags, meta title)



					$tags = wp_get_post_tags(get_the_ID());
					if( count( $tags ) > 0 ) {
						$tagTerms = "";
						foreach( $tags AS $i => $tag ) {
							$tagTerms .= " " . $tag->name;
						}
					} else {
						$tagTerms = get_the_title( get_the_ID() );
					}

					if( strlen( trim( $tagTerms ) ) > 0 ) {
// TODO: how many API calls does Amazon allow for?  (will this plugin "abuse" the Amazon policies?)
// TODO: add config for image
// TODO: add config for heading
// TODO: add config for price
// TODO: add config to show prime checkmark
// TODO: add config to display reviews
// TODO: add config to fetch only by top reviews
// TODO: add item count per row to config
// TODO: add horizontal scroller (pro)
// TODO: build in caching
// TODO: add a custom block with it's own options (pro)
// TODO: add configuration per blog post to change what to search for (pro)

// TODO: fetch ratings and reviews from here: https://www.amazon.com/gp/customer-reviews/widgets/average-customer-review/popover/ref=dpx_acr_pop_?contextId=dpx&asin=B000P0ZSHK
//       source: https://stackoverflow.com/questions/8279478/amazon-product-advertising-api-get-average-customer-rating?rq=4


						$searchString = '"Keywords": "' . $tagTerms . '"';
						$items = $this->callAmazonApi( self::AMZN_API_METHOD_SEARCHITEMS, $searchString );
						if( $items ) {
							$content .= '<div class="pip-amzpa-wrapper">';
								$headingText = get_option( $this->helper->getOptionName( PipAmzpaHelper::OPTION_AUTO_HEAD_TEXT ) );
								if( $headingText ) {
								$content .= '<div class="pip-amzpa-header">';
									$content .= $headingText;
								$content .= '</div>';
								}

								$content .= '<div class="pip-amzpa-container">';
								foreach( $items AS $i => $item ) {
									$content .= '<div class="pip-amzpa-item">';
										$content .= '<div class="pip-amzpa-image">';
											$content .= '<a class="pip-amzpa-link" href="'.$item->DetailPageURL.'" target="_blank">';
												$content .= '<img src="'.$item->Images->Primary->Medium->URL.'"
																  width="'.$item->Images->Primary->Medium->Width.'"
																  height="'.$item->Images->Primary->Medium->Height.'"
															/>';
											$content .= '</a>';
										$content .= '</div>';

										$content .= '<div class="pip-amzpa-title">';
											$content .= '<a class="pip-amzpa-link" href="'.$item->DetailPageURL.'" target="_blank">';
												$content .= $item->ItemInfo->Title->DisplayValue;
											$content .= '</a>';
										$content .= '</div>';

										$content .= '<div class="pip-amzpa-price">';
											if(
												   isset( $item->Offers )
												&& isset( $item->Offers->Listings )
												&& isset( $item->Offers->Listings[0] )
												&& isset( $item->Offers->Listings[0]->Price )
												&& isset( $item->Offers->Listings[0]->Price->Savings )
											) {
												$content .= '<span class="pip-amzpa-price-discount-percent">-'.$item->Offers->Listings[0]->Price->Savings->Percentage.'%</span>';
											}
											$content .= '<span class="pip-amzpa-price-amount">'.$item->Offers->Listings[0]->Price->DisplayAmount.'</span>';
											if( $item->Offers->Listings[0]->DeliveryInfo->IsPrimeEligible ) {
												$content .= '<div class="pip-amzpa-delivery-prime paapi5-pa-product-prime-icon"><span class="icon-prime-all"></span></div>';
											}
										$content .= '</div>';
									$content .= '</div>';
								}
								$content .= '</div>';
							$content .= '</div>';
						}
					}
				}
			}
		}
		return $content;
	}

	function getAdsByAsin(
		$asin,
		$title = '',
		$caption = '',
		$buttonText = 'Buy on Amazon',
		$showButton = 1,
		$showDetails = 0,
		$showPrime = 0
	) {
// TODO: check if configured
// TODO: remove check if configured form callAmazonApi
// TODO: reviews (configurable via shortcode)

		$searchString = '"ItemIds": ["' . $asin . '"]';
		$amznAds = $this->callAmazonApi( self::AMZN_API_METHOD_GETITEMS, $searchString );

		$content = '';
		if( $amznAds ) {
			if( strlen( trim( $title ) ) > 0 ) {
				$content .= '<div class="pip-amzpa-header pip-amzpa-header-asin">';
					$content .= esc_html( $title );
				$content .= '</div>';
			}

			$content .= '<div class="pip-amzpa-wrapper pip-amzpa-type-asin">';
				$content .= '<div class="pip-amzpa-container">';
				foreach( $amznAds AS $i => $item ) {
					$content .= '<div class="pip-amzpa-item">';
						$content .= '<div class="pip-amzpa-image">';
							$content .= '<a class="pip-amzpa-link" href="'.$item->DetailPageURL.'" target="_blank">';
								$content .= '<img src="'.$item->Images->Primary->Medium->URL.'"
												  width="'.$item->Images->Primary->Medium->Width.'"
												  height="'.$item->Images->Primary->Medium->Height.'"
											/>';
							$content .= '</a>';
						$content .= '</div>';

						$content .= '<div class="pip-amzpa-title">';
							$content .= '<a class="pip-amzpa-link" href="'.$item->DetailPageURL.'" target="_blank">';
								$content .= $item->ItemInfo->Title->DisplayValue;
							$content .= '</a>';
							
							if( $showDetails ) {
								if( property_exists( $item->ItemInfo, 'Features' ) && property_exists( $item->ItemInfo->Features, 'DisplayValues' ) ) {
									$content .= '<ul>';
									foreach( $item->ItemInfo->Features->DisplayValues AS $feature ) {
										$content .= '<li>' . $feature . '</li>';
									}
									$content .= '</ul>';
								}
							}
						$content .= '</div>';

						$content .= '<div class="pip-amzpa-price pip-amzpa-flex-align-right">';
							if( property_exists( $item->Offers->Listings[0]->Price, 'DisplayAmount' ) ) {
								$content .= '<span class="pip-amzpa-price-amount">'.$item->Offers->Listings[0]->Price->DisplayAmount.'</span>';
							}
							if( property_exists( $item->Offers->Listings[0]->Price, 'Savings' ) ) {
								$content .= '<span class="pip-amzpa-price-discount-percent">(-'.$item->Offers->Listings[0]->Price->Savings->Percentage.'%)</span>';
							}
							if( $showPrime ) {
								if( $item->Offers->Listings[0]->DeliveryInfo->IsPrimeEligible ) {
									$content .= '<div class="pip-amzpa-delivery-prime paapi5-pa-product-prime-icon"><span class="icon-prime-all"></span></div>';
								}
							}

							if( $showButton ) {
								$content .= '<a class="pip-amzpa-cta pip-amzpa-button" href="'.$item->DetailPageURL.'" target="_blank">';
									$content .= $buttonText;
								$content .= '</a>';
							}
						$content .= '</div>';
					$content .= '</div>';
				}
				$content .= '</div>';

				if( strlen( trim( $caption ) ) > 0 ) {
					$content .= '<div class="pip-amzpa-disclaimer pip-amzpa-disclaimer-asin">';
						$content .= '<i>' . esc_html( $caption ) . '</i>';
					$content .= '</div>';
				}

			$content .= '</div>';
		}

		return $content;
	}

}
