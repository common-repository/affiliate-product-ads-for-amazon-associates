=== Affiliate Product Ads for Amazon ===
Contributors: prominc
Tags: advertising, amazon, amazon-affiliate, amazon-affiliates, amazon-associate, amazon-associates
Requires at least: 6.3.0
Stable tag: 1.1.3
Tested up to: 6.6.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Amazon Product Advertising product ads automatically on WordPress Post Pages

== Description ==

Generate passive income from your WordPress posts by automatically displaying Amazon Product ads on posts with Amazon Affiliate links.

This plugin automatically detects posts that have links to Amazon in them already and adds an Amazon Product Ads display block at the bottom of the post with other related/suggested product ads.  With this feature enabled, Amazon's search algorithm is used to detect which products are best related to this post and displays them for your visitors to discover.

When the visitor clicks on one of these display ads and makes a purchase in Amazon, you will earn affiliate commission for sending that visitor to Amazon.  (subject to [Amazon terms and conditions](https://affiliate-program.amazon.com/help/operating/policies))

The product ads are generated on the server-side and are part of the page - they are not loaded after the page loads in JavaScript.  This method prevents most ad-blockers from removing the product ads.

Additionally, this plugin can display a disclaimer on posts that contain Amazon links as well as a site-wide disclaimer in the footer.  A disclaimer message is required by the United States FTC and Amazon Affiliates when displaying recommended product links for which you will be financially compensated.

= Included Features =

Each feature can be configured and customized to the needs of *your* site.

* Auto-add Amazon product display ads at the bottom of any post containing an `amzn.to` URL in it
* Auto-add a disclaimer at the bottom of any post containing an `amzn.to` URL in it
* Auto-add a disclaimer in the footer of all pages on the website

= Requirements =

**NOTE:** This plugin does require that you are a [registered Amazon Affiliate](https://affiliate-program.amazon.com/signup).  It utilizes the Amazon Product Advertising API 5.0 to obtain product ad information.  This does require approval and acceptance by Amazon to be able to use the Product Advertising API.  [More Information from Amazon](https://webservices.amazon.com/paapi5/documentation/register-for-pa-api.html)

== Screenshots ==

1. Earn passive income by automatically displaying Amazon Product Ads in blog posts
2. Amazon Product Ads can be automatically displayed on post pages
3. A disclaimer message can be displayed automatically on post pages containing Amazon links.  The disclaimer message and an optional header can be configured in the WordPress admin.
4. A disclaimer message can be displayed automatically at the bottom of the WordPress site.  The disclaimer message and an optional header can be configured in the WordPress admin.
5. Plugin configuration: Amazon credentials
6. Plugin configuration: Automatic Amazon Product Ads on post pages
7. Plugin configuration: Automatic disclaimer on post pages
8. Plugin configuration: Footer disclaimer

== Installation ==

This plugin requires acceptance from Amazon Product Advertising to work.  For that reason, obtain approval from Amazon before installing the plugin.  (It's a best practice to not have unused WordPress plugins installed if possible)

1. Bookmark this plugin so that after you get accepted to the Amazon Affiliate Program you can easily find this plugin to install.  :)
2. Register with the [Amazon Affiliate](https://affiliate-program.amazon.com/signup)
3. Wait.  Wait until Amazon approves you into their program
4. Install this plugin via the standard WordPress plugin installation methods
5. Activate the plugin
6. In your WordPress admin, click on **Amazon Product Advertising** in the left hand menu
7. Enter your Amazon credentials
8. Configure the plugin settings as desired
9. Save the configuration page
10. Collect money.  No extra setup is needed as the plugin is automatic from here - adding ads to any pages that have Amazon links on them.

== Frequently Asked Questions ==

= Does Amazon charge a fee to use this plugin? =

No.  There is no fee.  This uses the Amazon Product Advertising API they provide to approved users.  This may result in you earning money from Amazon.

= Is this plugin published by Amazon =

No.  This is an independent developed plugin that utilizes the Amazon Product Advertising API.  Amazon has made the Amazon Product Advertising API available to developers to use for creating product ads.  This plugin implements the necessary API calls for WordPress sites.

= Is this Amazon SiteStripe? =

Essentially yes.  This plugin is a way to automatically generate SiteStripe URLs and place them on a WordPress site.  Commission is earned from Amazon the same way that SiteStripe is earned.

= Are there any limits or restrictions? =

Amazon does have a limit to 8640 requests per day.  [source](https://webservices.amazon.com/paapi5/documentation/troubleshooting/api-rates.html)  This means that any page views that trigger this plugin to display product ads over 8640 per day will not have ads displayed.

= Which posts will automatically display Amazon Product Ads? =

Any post that already contains a link to Amazon in it will get automatic ads.  You have already indicated that the post has a promoted product from Amazon, thus this plugin will work to supplement that link with additional product ads generated based on what the Amazon search algorithm determines is popular at this time.

This plugin will first look for any **tags** on the post and use those as the keywords as the basis for the Amazon search criteria.  If no tags are set for the post then the post title is used.

== Disclaimer ==
This plugin will make backend API calls to external 3rd party of Amazon's servers at `https://webservices.amazon.com/paapi5/*` and load images on the frontend via `https://m.media-amazon.com/images/*`  These 3rd party resources are required to display the Amazon Product Ads on your site.

[Amazon License Agreements and Terms of Service](https://webservices.amazon.com/paapi5/documentation/read-la.html)

== Changelog ==

= 1.1 =
* Bug Fix: remove exception when no products found for the post.
* Bug Fix: section heading typo fix in settings

= 1.0 =
* Initial release
* Auto-Ads on posts containing `amzn.to` URLs
* Auto-Disclosure on posts containing `amzn.to` URLs
* Add disclosure to footer of site

= 1.1.1 =
* Standards updates

= 1.1.2 =
* Standards updates

= 1.1.3 =
* Resolve PHP warning when savings not available on product