-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2021 at 03:35 PM
-- Server version: 5.7.33-0ubuntu0.16.04.1-log
-- PHP Version: 7.3.26-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydrspace`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_block`
--

CREATE TABLE `cms_block` (
  `block_id` smallint(6) NOT NULL COMMENT 'Block ID',
  `title` varchar(255) NOT NULL COMMENT 'Block Title',
  `identifier` varchar(255) NOT NULL COMMENT 'Block String Identifier',
  `content` mediumtext COMMENT 'Block Content',
  `creation_time` timestamp NULL DEFAULT NULL COMMENT 'Block Creation Time',
  `update_time` timestamp NULL DEFAULT NULL COMMENT 'Block Modification Time',
  `is_active` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Is Block Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Block Table' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `cms_block`
--

INSERT INTO `cms_block` (`block_id`, `title`, `identifier`, `content`, `creation_time`, `update_time`, `is_active`) VALUES
(1, 'Footer Nav', 'footer_nav', '<nav class="global-footer--navigation">\r\n            <ol style="display: inline;">\r\n                <li style="color:#333366;" class="global-footer--navigation-category"><strong>Helpful Links</strong>\r\n                    <ol class="global-footer--navigation-options">\r\n                        <li><a href="{{secure_base_url}}/contact-us.htm">Contact Us</a></li>\r\n                        <li><a href="{{secure_base_url}}/globals/site-index.htm">Site Index</a></li>                        \r\n                        <li><a href="#" onclick="KAMPYLE_ONSITE_SDK.showForm(244)">Feedback</a></li>\r\n                        \r\n                    </ol>\r\n                </li>\r\n            </ol>\r\n            <ol style="display:inline;">\r\n                <li style="color:#333366;" class="global-footer--navigation-category"><strong>Texas Cities</strong>\r\n                    <ol class="global-footer--navigation-options">\r\n                       <li><a href="{{secure_base_url}}drs/location/arlington.html">Arlington</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/canton.html">Canton</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/dallas.html">Dallas</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/ennis.html">Ennis</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/fort_worth.html">Fort Worth</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/grand_prairie.html">Grand Prairie</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/irving.html">Irving</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/san_antonio.html">San Antonio</a></li>\r\n						<li><a href="{{secure_base_url}}drs/location/waxahachie.html">Waxahachie</a></li>\r\n                    </ol>\r\n                </li>\r\n                <li style="color:#333366;" class="global-footer--navigation-category"><strong>Specialties</strong>\r\n                    <ol class="global-footer--navigation-options">\r\n                         <li><a href="{{secure_base_url}}drs/specialty/orthopedics.html">Orthopedics</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/spine_doctors.html">Spine Doctors</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/hand_surgeons.html">Hand Surgeons</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/pain_management.html">Pain Management</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/podiatry.html">Podiatry</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/chiropractors.html">Chiropractors</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/dermatologists.html">Dermatologists</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/primary_care.html">Primary Care Physicians</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/physical_therapists.html">Physical Therapists</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/plastic_surgeons.html">Plastic Surgeons</a></li>\r\n						<li><a href="{{secure_base_url}}drs/specialty/psychiatrists.html">Psychiatrists</a></li>\r\n                    </ol>\r\n                </li>\r\n                <li style="color:#333366;" class="global-footer--navigation-category"><strong>Insurance Carriers</strong>\r\n                    <ol class="global-footer--navigation-options">\r\n                        <li><a href="{{secure_base_url}}drs/insurance/aetna.html">Aetna</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/blue_cross_blue_shield.html">Blue Cross Blue Shield</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/cigna.html">Cigna</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/first_choice_health.html">First Choice Health</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/tricare.html">TriCare</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/united_healthcare.html">UnitedHealthcare</a></li>\r\n						<li><a href="{{secure_base_url}}drs/insurance/wc.html">Workers\' Compensation</a></li>\r\n                    </ol>\r\n                </li>\r\n            </ol>\r\n        </nav>', '2020-10-26 11:05:40', '2020-10-26 11:05:41', 1),
(2, 'Footer Links Company', 'footer_links_company', '\n<div class="links">\n    <div class="block-title">\n        <strong><span>Company</span></strong>\n    </div>\n    <ul>\n        <li><a href="{{store url=""}}about-magento-demo-store/">About Us</a></li>\n        <li><a href="{{store url=""}}contacts/">Contact Us</a></li>\n        <li><a href="{{store url=""}}customer-service/">Customer Service</a></li>\n        <li><a href="{{store url=""}}privacy-policy-cookie-restriction-mode/">Privacy Policy</a></li>\n    </ul>\n</div>', '2020-10-26 11:05:41', '2020-10-26 11:05:41', 1),
(3, 'Cookie restriction notice', 'cookie_restriction_notice_block', '<p>This website requires cookies to provide all of its features. For more information on what data is contained in the cookies, please see our <a href="{{store direct_url="privacy-policy-cookie-restriction-mode"}}">Privacy Policy page</a>. To accept cookies from this site, please click the Allow button below.</p>', '2020-10-26 11:05:41', '2020-10-26 11:05:41', 1),
(4, 'Sub menu - Home', 'menu_content_04', '<div class="">\r\n	<ul role="menu" aria-hidden="true">\r\n		<li>\r\n			<p>Item 1</p>\r\n		</li>\r\n		<li>\r\n			<p>Item 2</p>\r\n		</li>\r\n		<li>\r\n			<p>Item 3</p>\r\n		</li>\r\n	</ul>\r\n</div>', NULL, NULL, 1),
(5, 'Sub menu - About Us', 'menu_content_05', '<div class="repos">	\r\n	<ul role="menu" aria-hidden="true">\r\n		<h3 class="desktop-only">&nbsp;</h3>\r\n		<li>\r\n			<p>Item 1</p>\r\n		</li>\r\n		<li>\r\n			<p>Item 2</p>\r\n		</li>\r\n	</ul>	\r\n</div>', NULL, NULL, 1),
(6, 'Sub menu - Blog', 'menu_content_06', '<div class="repos">	\r\n	<ul role="menu" aria-hidden="true">\r\n		<h3 class="desktop-only">&nbsp;</h3>\r\n		<li>\r\n			<p>Item 1</p>\r\n		</li>\r\n		<li>\r\n			<p>Item 2</p>\r\n		</li>\r\n	</ul>	\r\n</div>', NULL, NULL, 1),
(7, 'Sub menu - Patient Consultant', 'menu_content_07', '<div class="repos">\r\n	<ul role="menu" aria-hidden="true" class="tools">\r\n		\r\n		<li class="tool-stamps">\r\n			Item 1\r\n		</li>\r\n		<li class="tool-supplies">\r\n			Item 2\r\n		</li>\r\n	</ul>\r\n	\r\n	\r\n</div>', NULL, NULL, 1),
(8, 'Sub menu - Contact us', 'menu_content_08', '', NULL, NULL, 1),
(9, 'Sub menu-International', 'menu_content_09', '<div class="repos">\r\n	<ul role="menu" aria-hidden="true" class="tools">\r\n		<h3>Tools</h3>\r\n		\r\n	</ul>\r\n	<ul role="menu" aria-hidden="true">\r\n		<h3>Learn About</h3>\r\n		\r\n		</ul>\r\n		\r\n	</ul>\r\n	\r\n</div>', NULL, NULL, 0),
(10, 'Create account', 'new_customer_block', '<div class="row">\r\n	<div class="col-xs-12 col-sm-12 col-md-12">\r\n		<div class="well well-lg hidden-xs">\r\n			<h3>New to MyDrSpace?</h3>\r\n			<strong>Create a mydrspace.com Account to...</strong>\r\n			<ul id="new-to-usps-bullet-list">\r\n				<li>Provide low-risk urgent care for non-COVID-19 conditions, identify those persons who may need additional medical consultation or assessment, and refer as appropriate.</li>\r\n				<li>Access primary care providers and specialists, including mental and behavioral health, for chronic health conditions and medication management.</li>\r\n				<li>Participate in physical therapy, occupational therapy, and other modalities as a hybrid approach to in-person care for optimal health.</li>				\r\n				<li>Follow up with patients after hospitalization.</li>\r\n				<li>Provide non-emergent care to residents in long-term care facilities.</li>\r\n				<li>Provide education and training for HCP through peer-to-peer professional medical consultations (inpatient or outpatient) that are not locally available, particularly in rural areas.</li>\r\n			</ul>\r\n			<div class="btn-wrap">				\r\n				<a id="sign-up-button" href="{{secure_base_url}}registration" tabindex="19" class="btn btn-default btn-lg" title="Sign Up Now">Sign Up Now</a>\r\n			</div>\r\n		</div>\r\n		<div class="well well-lg visible-xs-block margin-top-20">\r\n			<a id="sign-up-link" href="{{secure_base_url}}registration" tabindex="19" title="New? Create an account">New? Create an account</a>\r\n		</div>	\r\n		<div class="hidden">\r\n			<strong>Browser Info</strong>\r\n			<ul class="list-unstyled">\r\n				<li>Browser Name: Chrome 8</li>\r\n				<li>Browser Type: WEB_BROWSER</li>\r\n				<li>Manufacturer: GOOGLE</li>\r\n				<li>Rendering Engine: WEBKIT</li>\r\n			</ul>\r\n			<strong>Browser Version Info</strong>\r\n			<ul class="list-unstyled">\r\n				<li>Version: 87.0.4280.66</li>\r\n				<li>Major Version: 87</li>\r\n				<li>Minor Version: 0</li>\r\n			</ul>\r\n			<strong>Operating System Info</strong>\r\n			<ul class="list-unstyled">\r\n				<li>Operating System: Windows 10</li>\r\n				<li>Manufacturer: MICROSOFT</li>\r\n				<li>Device Type: COMPUTER</li>\r\n			</ul>\r\n		</div>\r\n	</div>\r\n</div>', NULL, NULL, 1),
(11, 'Account create privacy', 'account_create_privacy', ' <div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-4 col-md-offset-4">\r\n                <div class="btn-wrap-single">\r\n                    <a tabindex="85" class="btn btn-primary btn-lg" id="btn-submit">Create Account</a>\r\n                </div>\r\n                <div style="margin-top: 34px;">\r\n                    <p><span class="required">*</span> <strong>Please read our Privacy Act Statement.</strong></p>\r\n                    <p class="small">Your information will be used to facilitate online registration, provide enrollment capability, and for the administration of\r\n                        Internet-based services or features. Collection is authorized by 39 U.S.C. 401, 403, &amp; 404.</p>\r\n                    <p class="small">Providing the information is voluntary, but if not provided, we may not process your registration request. We do not disclose\r\n                        your information to third parties without your consent, except to facilitate the transaction, to act on your behalf or request, or as legally required.\r\n                        This includes the following limited circumstances: to a congressional office on your behalf; to financial entities regarding financial transaction\r\n                        issues; to entities, including law enforcement, as required by law or in legal proceedings; and to contractors and other entities aiding us to\r\n                        fulfill the service (service providers).\r\n                        For more information regarding our privacy policies visit www.usps.com/privacypolicy or see our Privacy Policy link at the bottom of this page.\r\n                    </p>\r\n                </div>\r\n            </div>', NULL, NULL, 1),
(12, 'Footer Social Networks', 'footer_social_networks', ' <li>\r\n                <a style="text-decoration: none;" href="https://www.facebook.com/USPS?rf=108501355848630">\r\n                    <img alt="Image of Facebook social media icon." src="https://www.usps.com/global-elements/footer/images/social-facebook_1.png">\r\n                </a>\r\n            </li>\r\n            <li>\r\n                <a style="text-decoration: none;" href="https://twitter.com/usps">\r\n                    <img alt="Image of Twitter social media icon." src="https://www.usps.com/global-elements/footer/images/social-twitter_2.png">\r\n                </a></li>\r\n            <li>\r\n                <a style="text-decoration: none;" href="http://www.pinterest.com/uspsstamps/">\r\n                    <img alt="Image of Pinterest social media icon." src="https://www.usps.com/global-elements/footer/images/social-pinterest_6.png">\r\n                </a>\r\n            </li>\r\n            <li>\r\n                <a style="text-decoration: none;" href="https://www.youtube.com/usps">\r\n                    <img alt="Image of Youtube social media icon." src="https://www.usps.com/global-elements/footer/images/social-youtube_3.png">\r\n                </a>\r\n            </li>', NULL, NULL, 1),
(13, 'Idle Notification', 'idle_notification', '<div class="modal-dialog" role="document">\r\n        <div class="modal-content">\r\n            <div class="modal-header">\r\n                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>\r\n                <h4 class="modal-title" id="session-expiring-label">Session Expiring</h4>\r\n            </div>\r\n            <div class="modal-body">\r\n                <p>Due to inactivity, your session will expire in <span id="sessionCountdown"></span> and you will be automatically\r\n                    redirected to sign back on.<br><br>To keep your session alive, click \'Continue\'.</p>\r\n            </div>\r\n            <div class="modal-footer">\r\n                <button type="button" class="btn btn-primary btn-lg" id="session-refresh-btn">Continue</button>\r\n            </div>\r\n        </div>\r\n    </div>', NULL, NULL, 1),
(14, 'Google 2 factors authentication', 'google_auth_introductory', '<div class="row">\r\n	<div class="col-xs-12 col-sm-12 col-md-12">\r\n		<div class="well well-lg hidden-xs">\r\n			<h3>Google Two Factor Authentication</h3>\r\n			<strong>Google Authenticator on your phone</strong>\r\n			<ul id="new-to-usps-bullet-list">\r\n				<li style="display: inline-block;width: 170px;">\r\n				  <a class="btn btn-block btn-social" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">\r\n					<img src="{{secure_base_url}}/skin/frontend/base/default/images/android.png">\r\n				  </a>\r\n				</li>\r\n				<li style="display: inline-block;width: 170px;"> \r\n				  <a class="btn btn-block btn-social" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">\r\n					<img src="{{secure_base_url}}/skin/frontend/base/default/images/iphone.png">\r\n				  </a>\r\n				</li>		\r\n			</ul>			\r\n		</div>		\r\n	</div>\r\n</div>', NULL, NULL, 1),
(15, 'Password validate', 'password_validate', ' <div id="popover_content_wrapper" style="display: none">\r\n                            <strong>Your password must:</strong>\r\n                            <ul class="list-unstyled pswd_list">\r\n                                <li class="pswd_info_valid" id="pswd_info_username">Not match your username</li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_length">Be 8 to 50 characters long</li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_capital">Have at least one upper case letter</li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_lower">Have at least one lower case letter</li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_number">Have at least one number</li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_symbol">\r\n                                    Only allowable special characters (if used).<br>\r\n                                    <strong>- ( ) . &amp; @ ? \' # , / " + !</strong>\r\n                                </li>\r\n                                <li class="pswd_info_invalid" id="pswd_info_repeats">Not contain more than 2 consecutive repeat characters</li>\r\n                            </ul>\r\n                        </div>\r\n                        <div id="popover_content_wrapper_retype" style="display: none">\r\n                            <strong>Your re-type password:</strong>\r\n                            <ul class="list-unstyled pswd_list">\r\n                                <li class="pswd_info_invalid" id="retype_pswd_info_matching">Passwords Do Not Match</li>\r\n                                <li class="pswd_info_valid" id="retype_pswd_info_match">Passwords Successfully Match</li>\r\n                                <li class="pswd_info_invalid_password" id="retype_pswd_info_invalid">Invalid Password</li>\r\n                                <li class="pswd_info_matching_progress" id="retype_pswd_info_matching_progress">Passwords Matching So Far</li>\r\n                                <li class="pswd_info_begin" id="retype_pswd_info_begin">Begin Re-Typing Your Password</li>\r\n                            </ul>\r\n                        </div>', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_page`
--

CREATE TABLE `cms_page` (
  `page_id` smallint(6) NOT NULL COMMENT 'Page ID',
  `title` varchar(255) DEFAULT NULL COMMENT 'Page Title',
  `root_template` varchar(255) DEFAULT NULL COMMENT 'Page Template',
  `meta_keywords` text COMMENT 'Page Meta Keywords',
  `meta_description` text COMMENT 'Page Meta Description',
  `identifier` varchar(100) DEFAULT NULL COMMENT 'Page String Identifier',
  `content_heading` varchar(255) DEFAULT NULL COMMENT 'Page Content Heading',
  `content` mediumtext COMMENT 'Page Content',
  `creation_time` timestamp NULL DEFAULT NULL COMMENT 'Page Creation Time',
  `update_time` timestamp NULL DEFAULT NULL COMMENT 'Page Modification Time',
  `is_active` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Is Page Active',
  `sort_order` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Page Sort Order',
  `layout_update_xml` text COMMENT 'Page Layout Update Content',
  `custom_theme` varchar(100) DEFAULT NULL COMMENT 'Page Custom Theme',
  `custom_root_template` varchar(255) DEFAULT NULL COMMENT 'Page Custom Template',
  `custom_layout_update_xml` text COMMENT 'Page Custom Layout Update Content',
  `custom_theme_from` date DEFAULT NULL COMMENT 'Page Custom Theme Active From Date',
  `custom_theme_to` date DEFAULT NULL COMMENT 'Page Custom Theme Active To Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Page Table';

--
-- Dumping data for table `cms_page`
--

INSERT INTO `cms_page` (`page_id`, `title`, `root_template`, `meta_keywords`, `meta_description`, `identifier`, `content_heading`, `content`, `creation_time`, `update_time`, `is_active`, `sort_order`, `layout_update_xml`, `custom_theme`, `custom_root_template`, `custom_layout_update_xml`, `custom_theme_from`, `custom_theme_to`) VALUES
(1, '404 Not Found 1', 'two_columns_right', 'Page keywords', 'Page description', 'no-route', NULL, '\n<div class="page-title"><h1>Whoops, our bad...</h1></div>\n<dl>\n    <dt>The page you requested was not found, and we have a fine guess why.</dt>\n    <dd>\n        <ul class="disc">\n            <li>If you typed the URL directly, please make sure the spelling is correct.</li>\n            <li>If you clicked on a link to get here, the link is outdated.</li>\n        </ul>\n    </dd>\n</dl>\n<dl>\n    <dt>What can you do?</dt>\n    <dd>Have no fear, help is near! There are many ways you can get back on track with Magento Store.</dd>\n    <dd>\n        <ul class="disc">\n            <li><a href="#" onclick="history.go(-1); return false;">Go back</a> to the previous page.</li>\n            <li>Use the search bar at the top of the page to search for your products.</li>\n            <li>Follow these links to get you back on track!<br /><a href="{{store url=""}}">Store Home</a>\n            <span class="separator">|</span> <a href="{{store url="customer/account"}}">My Account</a></li>\n        </ul>\n    </dd>\n</dl>\n', '2020-12-08 01:24:11', '2020-12-08 01:24:11', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Home page', 'one_column', NULL, NULL, 'home', NULL, '<div class="jumbotron premium track-opt" style="height: 400px; background-image: url(&quot;/assets/images/holiday20/premium/deadlines1.jpg&quot;);" data-gtm-section="premium" data-gtm-vis-first-on-screen-2384666_2691="467" data-gtm-vis-total-visible-time-2384666_2691="100" data-gtm-vis-has-fired-2384666_2691="1">\r\n		\r\n			\r\n		</div>', '2020-12-08 01:24:11', '2020-12-08 01:24:12', 1, 0, '<!--<reference name="content">\n        <block type="catalog/product_new" name="home.catalog.product.new" alias="product_new" template="catalog/product/new.phtml" after="cms_page">\n            <action method="addPriceBlockType">\n                <type>bundle</type>\n                <block>bundle/catalog_product_price</block>\n                <template>bundle/catalog/product/price.phtml</template>\n            </action>\n        </block>\n        <block type="reports/product_viewed" name="home.reports.product.viewed" alias="product_viewed" template="reports/home_product_viewed.phtml" after="product_new">\n            <action method="addPriceBlockType">\n                <type>bundle</type>\n                <block>bundle/catalog_product_price</block>\n                <template>bundle/catalog/product/price.phtml</template>\n            </action>\n        </block>\n        <block type="reports/product_compared" name="home.reports.product.compared" template="reports/home_product_compared.phtml" after="product_viewed">\n            <action method="addPriceBlockType">\n                <type>bundle</type>\n                <block>bundle/catalog_product_price</block>\n                <template>bundle/catalog/product/price.phtml</template>\n            </action>\n        </block>\n    </reference>\n    <reference name="right">\n        <action method="unsetChild"><alias>right.reports.product.viewed</alias></action>\n        <action method="unsetChild"><alias>right.reports.product.compared</alias></action>\n    </reference>-->', NULL, NULL, NULL, NULL, NULL),
(3, 'About Us', 'two_columns_right', NULL, NULL, 'about-magento-demo-store', NULL, '\n<div class="page-title">\n    <h1>About Magento Store</h1>\n</div>\n<div class="col3-set">\n<div class="col-1"><p style="line-height:1.2em;"><small>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\nMorbi luctus. Duis lobortis. Nulla nec velit. Mauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec,\ntempus vitae, iaculis semper, pede.</small></p>\n<p style="color:#888; font:1.2em/1.4em georgia, serif;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\nMorbi luctus. Duis lobortis. Nulla nec velit. Mauris pulvinar erat non massa. Suspendisse tortor turpis,\nporta nec, tempus vitae, iaculis semper, pede. Cras vel libero id lectus rhoncus porta.</p></div>\n<div class="col-2">\n<p><strong style="color:#de036f;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus.\nDuis lobortis. Nulla nec velit.</strong></p>\n<p>Vivamus tortor nisl, lobortis in, faucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper.\nPhasellus id massa. Pellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada\nfames ac turpis egestas. Nunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac,\ntempus nec, tempor nec, justo. </p>\n<p>Maecenas ullamcorper, odio vel tempus egestas, dui orci faucibus orci, sit amet aliquet lectus dolor et quam.\nPellentesque consequat luctus purus. Nunc et risus. Etiam a nibh. Phasellus dignissim metus eget nisi.\nVestibulum sapien dolor, aliquet nec, porta ac, malesuada a, libero. Praesent feugiat purus eget est.\nNulla facilisi. Vestibulum tincidunt sapien eu velit. Mauris purus. Maecenas eget mauris eu orci accumsan feugiat.\nPellentesque eget velit. Nunc tincidunt.</p></div>\n<div class="col-3">\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper </p>\n<p><strong style="color:#de036f;">Maecenas ullamcorper, odio vel tempus egestas, dui orci faucibus orci,\nsit amet aliquet lectus dolor et quam. Pellentesque consequat luctus purus.</strong></p>\n<p>Nunc et risus. Etiam a nibh. Phasellus dignissim metus eget nisi.</p>\n<div class="divider"></div>\n<p>To all of you, from all of us at Magento Store - Thank you and Happy eCommerce!</p>\n<p style="line-height:1.2em;"><strong style="font:italic 2em Georgia, serif;">John Doe</strong><br />\n<small>Some important guy</small></p></div>\n</div>', '2020-12-08 01:24:11', '2020-12-08 01:24:11', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Customer Service', 'three_columns', NULL, NULL, 'customer-service', NULL, '<div class="page-title">\n<h1>Customer Service</h1>\n</div>\n<ul class="disc">\n<li><a href="#answer1">Shipping &amp; Delivery</a></li>\n<li><a href="#answer2">Privacy &amp; Security</a></li>\n<li><a href="#answer3">Returns &amp; Replacements</a></li>\n<li><a href="#answer4">Ordering</a></li>\n<li><a href="#answer5">Payment, Pricing &amp; Promotions</a></li>\n<li><a href="#answer6">Viewing Orders</a></li>\n<li><a href="#answer7">Updating Account Information</a></li>\n</ul>\n<dl>\n<dt id="answer1">Shipping &amp; Delivery</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\nPellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\nNunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\njusto.</dd>\n<dt id="answer2">Privacy &amp; Security</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\nPellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\nNunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\njusto.</dd>\n<dt id="answer3">Returns &amp; Replacements</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\nPellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\nNunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\njusto.</dd>\n<dt id="answer4">Ordering</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\nPellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\nNunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\njusto.</dd>\n<dt id="answer5">Payment, Pricing &amp; Promotions</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\nPellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\nNunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\njusto.</dd>\n<dt id="answer6">Viewing Orders</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\nMauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\nCras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\nfaucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\n Pellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\n Nunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\n justo.</dd>\n<dt id="answer7">Updating Account Information</dt>\n<dd>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi luctus. Duis lobortis. Nulla nec velit.\n Mauris pulvinar erat non massa. Suspendisse tortor turpis, porta nec, tempus vitae, iaculis semper, pede.\n Cras vel libero id lectus rhoncus porta. Suspendisse convallis felis ac enim. Vivamus tortor nisl, lobortis in,\n faucibus et, tempus at, dui. Nunc risus. Proin scelerisque augue. Nam ullamcorper. Phasellus id massa.\n Pellentesque nisl. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\n Nunc augue. Aenean sed justo non leo vehicula laoreet. Praesent ipsum libero, auctor ac, tempus nec, tempor nec,\n justo.</dd>\n</dl>', '2020-12-08 01:24:11', '2020-12-08 01:24:11', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Enable Cookies', 'one_column', NULL, NULL, 'enable-cookies', NULL, '<div class="std">\n    <ul class="messages">\n        <li class="notice-msg">\n            <ul>\n                <li>Please enable cookies in your web browser to continue.</li>\n            </ul>\n        </li>\n    </ul>\n    <div class="page-title">\n        <h1><a name="top"></a>What are Cookies?</h1>\n    </div>\n    <p>Cookies are short pieces of data that are sent to your computer when you visit a website.\n    On later visits, this data is then returned to that website. Cookies allow us to recognize you automatically\n    whenever you visit our site so that we can personalize your experience and provide you with better service.\n    We also use cookies (and similar browser data, such as Flash cookies) for fraud prevention and other purposes.\n     If your web browser is set to refuse cookies from our website, you will not be able to complete a purchase\n     or take advantage of certain features of our website, such as storing items in your Shopping Cart or\n     receiving personalized recommendations. As a result, we strongly encourage you to configure your web\n     browser to accept cookies from our website.</p>\n    <h2 class="subtitle">Enabling Cookies</h2>\n    <ul class="disc">\n        <li><a href="#ie7">Internet Explorer 7.x</a></li>\n        <li><a href="#ie6">Internet Explorer 6.x</a></li>\n        <li><a href="#firefox">Mozilla/Firefox</a></li>\n        <li><a href="#opera">Opera 7.x</a></li>\n    </ul>\n    <h3><a name="ie7"></a>Internet Explorer 7.x</h3>\n    <ol>\n        <li>\n            <p>Start Internet Explorer</p>\n        </li>\n        <li>\n            <p>Under the <strong>Tools</strong> menu, click <strong>Internet Options</strong></p>\n            <p><img src="{{skin url="images/cookies/ie7-1.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Click the <strong>Privacy</strong> tab</p>\n            <p><img src="{{skin url="images/cookies/ie7-2.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Click the <strong>Advanced</strong> button</p>\n            <p><img src="{{skin url="images/cookies/ie7-3.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Put a check mark in the box for <strong>Override Automatic Cookie Handling</strong>,\n            put another check mark in the <strong>Always accept session cookies </strong>box</p>\n            <p><img src="{{skin url="images/cookies/ie7-4.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Click <strong>OK</strong></p>\n            <p><img src="{{skin url="images/cookies/ie7-5.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Click <strong>OK</strong></p>\n            <p><img src="{{skin url="images/cookies/ie7-6.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Restart Internet Explore</p>\n        </li>\n    </ol>\n    <p class="a-top"><a href="#top">Back to Top</a></p>\n    <h3><a name="ie6"></a>Internet Explorer 6.x</h3>\n    <ol>\n        <li>\n            <p>Select <strong>Internet Options</strong> from the Tools menu</p>\n            <p><img src="{{skin url="images/cookies/ie6-1.gif"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Click on the <strong>Privacy</strong> tab</p>\n        </li>\n        <li>\n            <p>Click the <strong>Default</strong> button (or manually slide the bar down to <strong>Medium</strong>)\n            under <strong>Settings</strong>. Click <strong>OK</strong></p>\n            <p><img src="{{skin url="images/cookies/ie6-2.gif"}}" alt="" /></p>\n        </li>\n    </ol>\n    <p class="a-top"><a href="#top">Back to Top</a></p>\n    <h3><a name="firefox"></a>Mozilla/Firefox</h3>\n    <ol>\n        <li>\n            <p>Click on the <strong>Tools</strong>-menu in Mozilla</p>\n        </li>\n        <li>\n            <p>Click on the <strong>Options...</strong> item in the menu - a new window open</p>\n        </li>\n        <li>\n            <p>Click on the <strong>Privacy</strong> selection in the left part of the window. (See image below)</p>\n            <p><img src="{{skin url="images/cookies/firefox.png"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>Expand the <strong>Cookies</strong> section</p>\n        </li>\n        <li>\n            <p>Check the <strong>Enable cookies</strong> and <strong>Accept cookies normally</strong> checkboxes</p>\n        </li>\n        <li>\n            <p>Save changes by clicking <strong>Ok</strong>.</p>\n        </li>\n    </ol>\n    <p class="a-top"><a href="#top">Back to Top</a></p>\n    <h3><a name="opera"></a>Opera 7.x</h3>\n    <ol>\n        <li>\n            <p>Click on the <strong>Tools</strong> menu in Opera</p>\n        </li>\n        <li>\n            <p>Click on the <strong>Preferences...</strong> item in the menu - a new window open</p>\n        </li>\n        <li>\n            <p>Click on the <strong>Privacy</strong> selection near the bottom left of the window. (See image below)</p>\n            <p><img src="{{skin url="images/cookies/opera.png"}}" alt="" /></p>\n        </li>\n        <li>\n            <p>The <strong>Enable cookies</strong> checkbox must be checked, and <strong>Accept all cookies</strong>\n            should be selected in the &quot;<strong>Normal cookies</strong>&quot; drop-down</p>\n        </li>\n        <li>\n            <p>Save changes by clicking <strong>Ok</strong></p>\n        </li>\n    </ol>\n    <p class="a-top"><a href="#top">Back to Top</a></p>\n</div>\n', '2020-12-08 01:24:11', '2020-12-08 01:24:11', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Privacy Policy', 'one_column', NULL, NULL, 'privacy-policy-cookie-restriction-mode', 'Privacy Policy', '<p style="color: #ff0000; font-weight: bold; font-size: 13px">\n    Please replace this text with you Privacy Policy.\n    Please add any additional cookies your website uses below (e.g., Google Analytics)\n</p>\n<p>\n    This privacy policy sets out how {{config path="general/store_information/name"}} uses and protects any information\n    that you give {{config path="general/store_information/name"}} when you use this website.\n    {{config path="general/store_information/name"}} is committed to ensuring that your privacy is protected.\n    Should we ask you to provide certain information by which you can be identified when using this website,\n    then you can be assured that it will only be used in accordance with this privacy statement.\n    {{config path="general/store_information/name"}} may change this policy from time to time by updating this page.\n    You should check this page from time to time to ensure that you are happy with any changes.\n</p>\n<h2>What we collect</h2>\n<p>We may collect the following information:</p>\n<ul>\n    <li>name</li>\n    <li>contact information including email address</li>\n    <li>demographic information such as postcode, preferences and interests</li>\n    <li>other information relevant to customer surveys and/or offers</li>\n</ul>\n<p>\n    For the exhaustive list of cookies we collect see the <a href="#list">List of cookies we collect</a> section.\n</p>\n<h2>What we do with the information we gather</h2>\n<p>\n    We require this information to understand your needs and provide you with a better service,\n    and in particular for the following reasons:\n</p>\n<ul>\n    <li>Internal record keeping.</li>\n    <li>We may use the information to improve our products and services.</li>\n    <li>\n        We may periodically send promotional emails about new products, special offers or other information which we\n        think you may find interesting using the email address which you have provided.\n    </li>\n    <li>\n        From time to time, we may also use your information to contact you for market research purposes.\n        We may contact you by email, phone, fax or mail. We may use the information to customise the website\n        according to your interests.\n    </li>\n</ul>\n<h2>Security</h2>\n<p>\n    We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure,\n    we have put in place suitable physical, electronic and managerial procedures to safeguard and secure\n    the information we collect online.\n</p>\n<h2>How we use cookies</h2>\n<p>\n    A cookie is a small file which asks permission to be placed on your computer\'s hard drive.\n    Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit\n    a particular site. Cookies allow web applications to respond to you as an individual. The web application\n    can tailor its operations to your needs, likes and dislikes by gathering and remembering information about\n    your preferences.\n</p>\n<p>\n    We use traffic log cookies to identify which pages are being used. This helps us analyse data about web page traffic\n    and improve our website in order to tailor it to customer needs. We only use this information for statistical\n    analysis purposes and then the data is removed from the system.\n</p>\n<p>\n    Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful\n    and which you do not. A cookie in no way gives us access to your computer or any information about you,\n    other than the data you choose to share with us. You can choose to accept or decline cookies.\n    Most web browsers automatically accept cookies, but you can usually modify your browser setting\n    to decline cookies if you prefer. This may prevent you from taking full advantage of the website.\n</p>\n<h2>Links to other websites</h2>\n<p>\n    Our website may contain links to other websites of interest. However, once you have used these links\n    to leave our site, you should note that we do not have any control over that other website.\n    Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst\n    visiting such sites and such sites are not governed by this privacy statement.\n    You should exercise caution and look at the privacy statement applicable to the website in question.\n</p>\n<h2>Controlling your personal information</h2>\n<p>You may choose to restrict the collection or use of your personal information in the following ways:</p>\n<ul>\n    <li>\n        whenever you are asked to fill in a form on the website, look for the box that you can click to indicate\n        that you do not want the information to be used by anybody for direct marketing purposes\n    </li>\n    <li>\n        if you have previously agreed to us using your personal information for direct marketing purposes,\n        you may change your mind at any time by writing to or emailing us at\n        {{config path="trans_email/ident_general/email"}}\n    </li>\n</ul>\n<p>\n    We will not sell, distribute or lease your personal information to third parties unless we have your permission\n    or are required by law to do so. We may use your personal information to send you promotional information\n    about third parties which we think you may find interesting if you tell us that you wish this to happen.\n</p>\n<p>\n    You may request details of personal information which we hold about you under the Data Protection Act 1998.\n    A small fee will be payable. If you would like a copy of the information held on you please write to\n    {{config path="general/store_information/address"}}.\n</p>\n<p>\n    If you believe that any information we are holding on you is incorrect or incomplete,\n    please write to or email us as soon as possible, at the above address.\n    We will promptly correct any information found to be incorrect.\n</p>\n<h2><a name="list"></a>List of cookies we collect</h2>\n<p>The table below lists the cookies we collect and what information they store.</p>\n<table class="data-table">\n    <thead>\n        <tr>\n            <th>COOKIE name</th>\n            <th>COOKIE Description</th>\n        </tr>\n    </thead>\n    <tbody>\n        <tr>\n            <th>CART</th>\n            <td>The association with your shopping cart.</td>\n        </tr>\n        <tr>\n            <th>CATEGORY_INFO</th>\n            <td>Stores the category info on the page, that allows to display pages more quickly.</td>\n        </tr>\n        <tr>\n            <th>COMPARE</th>\n            <td>The items that you have in the Compare Products list.</td>\n        </tr>\n        <tr>\n            <th>CURRENCY</th>\n            <td>Your preferred currency</td>\n        </tr>\n        <tr>\n            <th>CUSTOMER</th>\n            <td>An encrypted version of your customer id with the store.</td>\n        </tr>\n        <tr>\n            <th>CUSTOMER_AUTH</th>\n            <td>An indicator if you are currently logged into the store.</td>\n        </tr>\n        <tr>\n            <th>CUSTOMER_INFO</th>\n            <td>An encrypted version of the customer group you belong to.</td>\n        </tr>\n        <tr>\n            <th>CUSTOMER_SEGMENT_IDS</th>\n            <td>Stores the Customer Segment ID</td>\n        </tr>\n        <tr>\n            <th>EXTERNAL_NO_CACHE</th>\n            <td>A flag, which indicates whether caching is disabled or not.</td>\n        </tr>\n        <tr>\n            <th>FRONTEND</th>\n            <td>You sesssion ID on the server.</td>\n        </tr>\n        <tr>\n            <th>GUEST-VIEW</th>\n            <td>Allows guests to edit their orders.</td>\n        </tr>\n        <tr>\n            <th>LAST_CATEGORY</th>\n            <td>The last category you visited.</td>\n        </tr>\n        <tr>\n            <th>LAST_PRODUCT</th>\n            <td>The most recent product you have viewed.</td>\n        </tr>\n        <tr>\n            <th>NEWMESSAGE</th>\n            <td>Indicates whether a new message has been received.</td>\n        </tr>\n        <tr>\n            <th>NO_CACHE</th>\n            <td>Indicates whether it is allowed to use cache.</td>\n        </tr>\n        <tr>\n            <th>PERSISTENT_SHOPPING_CART</th>\n            <td>A link to information about your cart and viewing history if you have asked the site.</td>\n        </tr>\n        <tr>\n            <th>POLL</th>\n            <td>The ID of any polls you have recently voted in.</td>\n        </tr>\n        <tr>\n            <th>POLLN</th>\n            <td>Information on what polls you have voted on.</td>\n        </tr>\n        <tr>\n            <th>RECENTLYCOMPARED</th>\n            <td>The items that you have recently compared.            </td>\n        </tr>\n        <tr>\n            <th>STF</th>\n            <td>Information on products you have emailed to friends.</td>\n        </tr>\n        <tr>\n            <th>STORE</th>\n            <td>The store view or language you have selected.</td>\n        </tr>\n        <tr>\n            <th>USER_ALLOWED_SAVE_COOKIE</th>\n            <td>Indicates whether a customer allowed to use cookies.</td>\n        </tr>\n        <tr>\n            <th>VIEWED_PRODUCT_IDS</th>\n            <td>The products that you have recently viewed.</td>\n        </tr>\n        <tr>\n            <th>WISHLIST</th>\n            <td>An encrypted list of products added to your Wishlist.</td>\n        </tr>\n        <tr>\n            <th>WISHLIST_CNT</th>\n            <td>The number of items in your Wishlist.</td>\n        </tr>\n    </tbody>\n</table>', '2020-12-08 01:24:11', '2020-12-08 01:24:11', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_page_store`
--

CREATE TABLE `cms_page_store` (
  `page_id` smallint(6) NOT NULL COMMENT 'Page ID',
  `store_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Store ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Page To Store Linkage Table';

--
-- Dumping data for table `cms_page_store`
--

INSERT INTO `cms_page_store` (`page_id`, `store_id`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_config_data`
--

CREATE TABLE `core_config_data` (
  `config_id` int(10) UNSIGNED NOT NULL COMMENT 'Config Id',
  `scope` varchar(8) NOT NULL DEFAULT 'default' COMMENT 'Config Scope',
  `scope_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Config Scope Id',
  `path` varchar(255) NOT NULL DEFAULT 'general' COMMENT 'Config Path',
  `value` text COMMENT 'Config Value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Config Data' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `core_config_data`
--

INSERT INTO `core_config_data` (`config_id`, `scope`, `scope_id`, `path`, `value`) VALUES
(1, 'default', 0, 'web/unsecure/base_url', 'https://www.huynhdo.us/mydrspace'),
(2, 'default', 0, 'web/secure/base_url', 'https://www.huynhdo.us/mydrspace'),
(5, 'default', 0, 'web/url/use_store', '0'),
(6, 'default', 1, 'web/url/redirect_to_base', '0'),
(7, 'default', 0, 'web/unsecure/base_link_url', '{{unsecure_base_url}}'),
(8, 'default', 0, 'web/unsecure/base_skin_url', '{{unsecure_base_url}}skin/'),
(9, 'default', 0, 'web/unsecure/base_media_url', '{{unsecure_base_url}}media/'),
(10, 'default', 0, 'web/unsecure/base_js_url', '{{unsecure_base_url}}js/'),
(11, 'default', 0, 'web/secure/base_link_url', '{{secure_base_url}}'),
(12, 'default', 0, 'web/secure/base_skin_url', '{{secure_base_url}}skin/'),
(13, 'default', 0, 'web/secure/base_media_url', '{{secure_base_url}}media/'),
(14, 'default', 0, 'web/secure/base_js_url', '{{secure_base_url}}js/'),
(15, 'default', 0, 'web/secure/use_in_frontend', '1'),
(16, 'default', 0, 'web/secure/use_in_adminhtml', '1'),
(17, 'default', 0, 'web/secure/offloader_header', 'SSL_OFFLOADED'),
(18, 'default', 0, 'web/cookie/cookie_lifetime', '0'),
(19, 'default', 0, 'web/cookie/cookie_path', '/'),
(20, 'default', 0, 'web/cookie/cookie_domain', '.huynhdo.us'),
(21, 'default', 0, 'web/cookie/cookie_httponly', '1'),
(22, 'default', 0, 'web/cookie/cookie_restriction', '0'),
(23, 'default', 0, 'web/session/use_remote_addr', '0'),
(25, 'default', 0, 'web/session/use_http_via', '0'),
(26, 'default', 0, 'web/session/use_http_x_forwarded_for', '0'),
(27, 'default', 0, 'web/session/use_http_user_agent', '1'),
(28, 'default', 0, 'web/session/use_frontend_sid', '0'),
(29, 'default', 0, 'web/browser_capabilities/cookies', '1'),
(30, 'default', 0, 'web/browser_capabilities/javascript', '1'),
(31, 'default', 0, 'web/login/failed_login', '3'),
(32, 'default', 0, 'web/login/failed_login_seconds', '30'),
(33, 'default', 0, 'web/captcha/enable', '1'),
(34, 'default', 0, 'mail/email_from', 'noreply@mydrspace.com'),
(35, 'default', 0, 'mail/email_from_name', 'Adminstrator'),
(36, 'default', 0, 'web/login/failed_g2f_max', '3');

-- --------------------------------------------------------

--
-- Table structure for table `core_flag`
--

CREATE TABLE `core_flag` (
  `flag_id` int(10) UNSIGNED NOT NULL COMMENT 'Flag Id',
  `flag_code` varchar(255) NOT NULL COMMENT 'Flag Code',
  `state` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Flag State',
  `flag_data` text COMMENT 'Flag Data',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date of Last Flag Update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Flag';

--
-- Dumping data for table `core_flag`
--

INSERT INTO `core_flag` (`flag_id`, `flag_code`, `state`, `flag_data`, `last_update`) VALUES
(1, 'admin_notification_survey', 0, 'a:1:{s:13:"survey_viewed";b:1;}', '2020-10-26 11:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `core_layout_link`
--

CREATE TABLE `core_layout_link` (
  `layout_link_id` int(11) NOT NULL,
  `store_id` smallint(6) NOT NULL,
  `area` varchar(64) NOT NULL,
  `package` varchar(64) NOT NULL,
  `theme` varchar(64) NOT NULL,
  `layout_update_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `core_layout_update`
--

CREATE TABLE `core_layout_update` (
  `layout_update_id` int(10) NOT NULL,
  `handle` varchar(255) NOT NULL,
  `xml` text NOT NULL,
  `sort_order` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `core_menu`
--

CREATE TABLE `core_menu` (
  `menu_id` tinyint(4) NOT NULL,
  `menu_title` varchar(60) NOT NULL,
  `has_role` varchar(60) NOT NULL,
  `tab_index` tinyint(4) NOT NULL,
  `is_active_parent` tinyint(4) NOT NULL DEFAULT '1',
  `position_id` tinyint(4) NOT NULL,
  `li_header_class` varchar(60) NOT NULL,
  `menu_title_class` varchar(60) NOT NULL,
  `has_qt` varchar(60) NOT NULL,
  `has_link` varchar(100) NOT NULL,
  `menu_link_id` varchar(60) NOT NULL,
  `content_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_menu`
--

INSERT INTO `core_menu` (`menu_id`, `menu_title`, `has_role`, `tab_index`, `is_active_parent`, `position_id`, `li_header_class`, `menu_title_class`, `has_qt`, `has_link`, `menu_link_id`, `content_id`) VALUES
(1, 'Home', 'menuitem', 0, 1, 0, 'menuheader', 'menuitem', 'qt-nav', '#', 'navquicktools', 4),
(2, 'About', 'menuitem', 0, 1, 1, 'menuheader', 'menuitem', '', 'ship.html', 'navmailship', 5),
(3, 'Blog', 'menuitem', 0, 1, 2, 'menuheader', 'menuitem', '', 'manage.html', 'navtrackmanage', 6),
(4, 'Patient Care Consultant', 'menuitem', 0, 1, 3, 'menuheader', 'menuitem', '', 'store.html', 'navpostalstore', 7),
(5, 'Contact Us', 'menuitem', 0, 1, 4, 'menuheader', 'menuitem', '', 'bussiness.html', 'navbusiness', 8),
(6, 'International', 'menuitem', 0, 0, 5, 'menuheader', 'menuitem', '', 'international.html', 'navinternational', 9);

-- --------------------------------------------------------

--
-- Table structure for table `core_store`
--

CREATE TABLE `core_store` (
  `store_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Store Id',
  `code` varchar(32) DEFAULT NULL COMMENT 'Code',
  `website_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Website Id',
  `group_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group Id',
  `name` varchar(255) NOT NULL COMMENT 'Store Name',
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Store Sort Order',
  `is_active` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Store Activity'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores';

--
-- Dumping data for table `core_store`
--

INSERT INTO `core_store` (`store_id`, `code`, `website_id`, `group_id`, `name`, `sort_order`, `is_active`) VALUES
(1, 'default', 1, 1, 'Default Store View', 0, 1),
(2, 'admin', 0, 0, 'Admin', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_variable`
--

CREATE TABLE `core_variable` (
  `variable_id` int(10) UNSIGNED NOT NULL COMMENT 'Variable Id',
  `code` varchar(255) DEFAULT NULL COMMENT 'Variable Code',
  `name` varchar(255) DEFAULT NULL COMMENT 'Variable Name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Variables';

-- --------------------------------------------------------

--
-- Table structure for table `core_variable_value`
--

CREATE TABLE `core_variable_value` (
  `value_id` int(10) UNSIGNED NOT NULL COMMENT 'Variable Value Id',
  `variable_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Variable Id',
  `store_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Store Id',
  `plain_value` text COMMENT 'Plain Text Value',
  `html_value` text COMMENT 'Html Value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Variable Value';

-- --------------------------------------------------------

--
-- Table structure for table `core_website`
--

CREATE TABLE `core_website` (
  `website_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Website Id',
  `code` varchar(32) DEFAULT NULL COMMENT 'Code',
  `name` varchar(64) DEFAULT NULL COMMENT 'Website Name',
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Sort Order',
  `default_group_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Default Group Id',
  `is_default` smallint(5) UNSIGNED DEFAULT '0' COMMENT 'Defines Is Website Default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Websites';

--
-- Dumping data for table `core_website`
--

INSERT INTO `core_website` (`website_id`, `code`, `name`, `sort_order`, `default_group_id`, `is_default`) VALUES
(1, 'base', 'Main Website', 0, 1, 1),
(2, 'admin', 'Admin', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_password`
--

CREATE TABLE `login_password` (
  `password_id` int(4) NOT NULL,
  `user_name` varchar(32) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_password`
--

INSERT INTO `login_password` (`password_id`, `user_name`, `password_hash`, `update_time`) VALUES
(1, 'hdo', '613bf09e6dd803895b390b1fb03c750886cb09a28d832a50784b5e4fb41781cc', '2021-01-25 18:58:28'),
(12, 'hoanghwd', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2021-02-11 23:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

CREATE TABLE `login_user` (
  `user_id` int(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `password_reset_require` tinyint(4) DEFAULT '0',
  `password_reset_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `lognum` int(11) NOT NULL DEFAULT '0',
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `role_id` tinyint(4) NOT NULL DEFAULT '2',
  `user_activation_hash` varchar(256) NOT NULL,
  `failed_login_nums` tinyint(4) NOT NULL,
  `user_last_failed` int(11) NOT NULL,
  `user_registration_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_registration_ip` varchar(60) NOT NULL,
  `authorization_code` varchar(11) DEFAULT NULL,
  `auth_code_failed_nums` int(11) NOT NULL DEFAULT '0',
  `security_question_1` int(4) NOT NULL,
  `security_answer_1` varchar(100) NOT NULL,
  `security_question_2` int(4) NOT NULL,
  `security_answer_2` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`user_id`, `username`, `password`, `password_reset_require`, `password_reset_time`, `lognum`, `logdate`, `is_active`, `role_id`, `user_activation_hash`, `failed_login_nums`, `user_last_failed`, `user_registration_datetime`, `user_registration_ip`, `authorization_code`, `auth_code_failed_nums`, `security_question_1`, `security_answer_1`, `security_question_2`, `security_answer_2`) VALUES
(317, 'hdo', '613bf09e6dd803895b390b1fb03c750886cb09a28d832a50784b5e4fb41781cc', 0, '2021-02-08 19:22:51', 1133, '2021-02-08 19:22:51', 1, 0, '', 0, 0, '2020-11-19 20:53:40', '', '1611601089', 0, 0, '0', 0, '0'),
(345, 'hoanghwd', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 0, NULL, 0, '2021-02-11 23:32:53', 0, 1, '36c43085f2ea093ffec71dcb8f5ef4d2091ddad4d548a377d2cefba785784565', 0, 0, '2021-02-11 23:32:53', '192.168.50.1', NULL, 0, 2, 'test', 3, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `mail_template`
--

CREATE TABLE `mail_template` (
  `template_id` int(11) NOT NULL,
  `identifier` varchar(60) NOT NULL,
  `content` mediumtext NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `security_question`
--

CREATE TABLE `security_question` (
  `question_id` int(11) NOT NULL,
  `question_value` varchar(128) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `security_question`
--

INSERT INTO `security_question` (`question_id`, `question_value`, `is_active`) VALUES
(1, 'In what city were you born?', 1),
(4, 'What is the name of your pet?', 1),
(5, 'What is your favorite food to eat?', 1),
(6, 'What is your favorite holiday?', 1),
(7, 'What is your favorite movie?', 1),
(8, 'What is your favorite sport?', 1),
(9, 'What is your mother\'s maiden name?', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(10) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(10) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `contact_from_us` varchar(20) NOT NULL,
  `contact_from_partner` varchar(10) NOT NULL,
  `medical_type` int(4) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `office_no` varchar(20) DEFAULT NULL,
  `ext` int(4) NOT NULL,
  `fax_no` varchar(20) DEFAULT NULL,
  `address_1` varchar(100) DEFAULT NULL,
  `address_2` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `practice_name` varchar(100) DEFAULT NULL,
  `insurance` text,
  `language` text,
  `education` varchar(100) DEFAULT NULL,
  `school_of_graduate` varchar(100) DEFAULT NULL,
  `specialties` text,
  `hospital_affiliations` text,
  `residency_program_1` varchar(100) DEFAULT NULL,
  `residency_program_2` varchar(100) DEFAULT NULL,
  `professional_statement` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `title`, `first_name`, `middle_name`, `last_name`, `email`, `contact_from_us`, `contact_from_partner`, `medical_type`, `phone_no`, `office_no`, `ext`, `fax_no`, `address_1`, `address_2`, `city`, `state`, `zip`, `practice_name`, `insurance`, `language`, `education`, `school_of_graduate`, `specialties`, `hospital_affiliations`, `residency_program_1`, `residency_program_2`, `professional_statement`) VALUES
(1, 0, '', 'HUYNH', '', 'DO', 'hdohwd@yahoo.com', '', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1000, 20, '', 'Dr', '', 'Feelgood', 'test1@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, '201 S Madison Ave', '', 'Dallas', 'TX', '75208', '', '', '', '', NULL, '', '', NULL, NULL, NULL),
(1002, 22, '', 'RobertPefKJ', '', 'RobertPefKJ', 'test2@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'Le Mans', 'Le Mans', 'Le Mans', 'SD', '', 'RobertPef', '', '', '', NULL, '', '', NULL, NULL, NULL),
(1003, 23, '', 'AngeloEsotsTV', '', 'AngeloEsotsTV', 'test3@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'Mariupol', 'Mariupol', 'Mariupol', 'NJ', '', 'AngeloEsots', '', '', '', NULL, '', '', NULL, NULL, NULL),
(1004, 24, '', 'CaseyCetJB', '', 'CaseyCetJB', 'test4@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'Lianyungang', 'Lianyungang', 'Lianyungang', 'VT', '', 'CaseyCet', '', '', '', NULL, '', '', NULL, NULL, NULL),
(1133, 154, '', 'Kai', '', 'tab', 'test5@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'TEST ADD', '120', 'Dallas', 'TX', '123123', 'Test Practice', 'TEST INSUR', 'TEST ENGLISH', 'Test EDU', NULL, 'TEST SPEC', 'TEST HOSPITAL', NULL, NULL, 'Test Professional'),
(1134, 155, '', 'Kai', '', 'tab', 'test6@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'TEST ADD', '120', 'Dallas', 'TX', '123123', 'Test Practice', 'TEST INSUR', 'TEST ENGLISH', 'Test EDU', NULL, 'TEST SPEC', 'TEST HOSPITAL', NULL, NULL, 'Test Professional'),
(1186, 212, '', 'Road', '', 'Map', 'test7@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, '201 S Madison Ave', '', 'Dallas', 'TX', '75208', 'practice_name', 'test', 'language', 'education', NULL, 'specialties', 'hospital_affiliations', NULL, NULL, 'test'),
(1256, 286, '', 'John', '', 'Zacharias', 'test8@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, '201 S Madison Ave', '', 'Dallas', 'TX', '75208', 'Advantage', 'some', 'English', 'some', NULL, 'Family', 'some', NULL, NULL, ''),
(1263, 293, '', 'John', '', 'Zacharias', 'test9@hotmail.com', '', '', NULL, NULL, NULL, 0, NULL, '201 S Madison Ave', '', 'Dallas', 'TX', '75208', 'asdv', 'dfsa', 'em', 'dfas', NULL, 'famil', 'dfa', NULL, NULL, ''),
(1283, 317, '', 'Anthony', '', 'Do', 'hdohwd@gmail.com', '', '', NULL, NULL, NULL, 0, NULL, 'DFSDF', 'FSDFDFSDF', 'FSDF', 'CA', '92646', 'ED', 'DD', 'D', 'ED', NULL, 'ED', 'D', NULL, NULL, 'fsfsdf'),
(1289, 345, 'Mrs', 'HUYNH', 'm', 'Do', 'hdohwd@hotmail.com', 'true', 'true', 1, NULL, '7142309879', 0, NULL, '123 ST', '', 'Huntington Beach', 'CA', '92646', 'H', 'BLUE SHEILD', 'EN', NULL, 'LOMA LINDA', 'TEST', 'FVSD', 'FOUTAIN VALLEY', 'TEST', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `role_id` tinyint(4) NOT NULL,
  `role_description` varchar(60) NOT NULL,
  `permission_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`role_id`, `role_description`, `permission_id`) VALUES
(0, 'Administrator', 0),
(1, 'Doctor', 1),
(2, 'Patient', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_security_answer`
--

CREATE TABLE `user_security_answer` (
  `answer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selected_question_id` int(11) NOT NULL,
  `user_answer` varchar(120) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_block`
--
ALTER TABLE `cms_block`
  ADD PRIMARY KEY (`block_id`) USING BTREE;

--
-- Indexes for table `cms_page`
--
ALTER TABLE `cms_page`
  ADD PRIMARY KEY (`page_id`),
  ADD KEY `IDX_CMS_PAGE_IDENTIFIER` (`identifier`);

--
-- Indexes for table `cms_page_store`
--
ALTER TABLE `cms_page_store`
  ADD PRIMARY KEY (`page_id`,`store_id`),
  ADD KEY `IDX_CMS_PAGE_STORE_STORE_ID` (`store_id`);

--
-- Indexes for table `core_config_data`
--
ALTER TABLE `core_config_data`
  ADD PRIMARY KEY (`config_id`) USING BTREE,
  ADD UNIQUE KEY `UNQ_CORE_CONFIG_DATA_SCOPE_SCOPE_ID_PATH` (`scope`,`scope_id`,`path`) USING BTREE;

--
-- Indexes for table `core_flag`
--
ALTER TABLE `core_flag`
  ADD PRIMARY KEY (`flag_id`) USING BTREE,
  ADD KEY `IDX_CORE_FLAG_LAST_UPDATE` (`last_update`) USING BTREE;

--
-- Indexes for table `core_layout_link`
--
ALTER TABLE `core_layout_link`
  ADD PRIMARY KEY (`layout_link_id`) USING BTREE;

--
-- Indexes for table `core_layout_update`
--
ALTER TABLE `core_layout_update`
  ADD PRIMARY KEY (`layout_update_id`) USING BTREE;

--
-- Indexes for table `core_menu`
--
ALTER TABLE `core_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `core_store`
--
ALTER TABLE `core_store`
  ADD PRIMARY KEY (`store_id`) USING BTREE,
  ADD UNIQUE KEY `UNQ_CORE_STORE_CODE` (`code`) USING BTREE,
  ADD KEY `IDX_CORE_STORE_WEBSITE_ID` (`website_id`) USING BTREE,
  ADD KEY `IDX_CORE_STORE_IS_ACTIVE_SORT_ORDER` (`is_active`,`sort_order`) USING BTREE,
  ADD KEY `IDX_CORE_STORE_GROUP_ID` (`group_id`) USING BTREE;

--
-- Indexes for table `core_variable`
--
ALTER TABLE `core_variable`
  ADD PRIMARY KEY (`variable_id`) USING BTREE,
  ADD UNIQUE KEY `UNQ_CORE_VARIABLE_CODE` (`code`) USING BTREE;

--
-- Indexes for table `core_variable_value`
--
ALTER TABLE `core_variable_value`
  ADD PRIMARY KEY (`value_id`) USING BTREE,
  ADD UNIQUE KEY `UNQ_CORE_VARIABLE_VALUE_VARIABLE_ID_STORE_ID` (`variable_id`,`store_id`) USING BTREE,
  ADD KEY `IDX_CORE_VARIABLE_VALUE_VARIABLE_ID` (`variable_id`) USING BTREE,
  ADD KEY `IDX_CORE_VARIABLE_VALUE_STORE_ID` (`store_id`) USING BTREE;

--
-- Indexes for table `core_website`
--
ALTER TABLE `core_website`
  ADD PRIMARY KEY (`website_id`) USING BTREE,
  ADD UNIQUE KEY `UNQ_CORE_WEBSITE_CODE` (`code`) USING BTREE,
  ADD KEY `IDX_CORE_WEBSITE_SORT_ORDER` (`sort_order`) USING BTREE,
  ADD KEY `IDX_CORE_WEBSITE_DEFAULT_GROUP_ID` (`default_group_id`) USING BTREE;

--
-- Indexes for table `login_password`
--
ALTER TABLE `login_password`
  ADD PRIMARY KEY (`password_id`),
  ADD UNIQUE KEY `password_hash` (`password_hash`);

--
-- Indexes for table `login_user`
--
ALTER TABLE `login_user`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `mail_template`
--
ALTER TABLE `mail_template`
  ADD PRIMARY KEY (`template_id`),
  ADD UNIQUE KEY `identifier` (`identifier`);

--
-- Indexes for table `security_question`
--
ALTER TABLE `security_question`
  ADD PRIMARY KEY (`question_id`),
  ADD UNIQUE KEY `question_value` (`question_value`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `user_id_3` (`user_id`),
  ADD KEY `user_id_4` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user_security_answer`
--
ALTER TABLE `user_security_answer`
  ADD PRIMARY KEY (`answer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_block`
--
ALTER TABLE `cms_block`
  MODIFY `block_id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Block ID', AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `cms_page`
--
ALTER TABLE `cms_page`
  MODIFY `page_id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Page ID', AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `core_config_data`
--
ALTER TABLE `core_config_data`
  MODIFY `config_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Config Id', AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `core_flag`
--
ALTER TABLE `core_flag`
  MODIFY `flag_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Flag Id', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `core_layout_link`
--
ALTER TABLE `core_layout_link`
  MODIFY `layout_link_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_layout_update`
--
ALTER TABLE `core_layout_update`
  MODIFY `layout_update_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_menu`
--
ALTER TABLE `core_menu`
  MODIFY `menu_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `core_store`
--
ALTER TABLE `core_store`
  MODIFY `store_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Store Id', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `core_variable`
--
ALTER TABLE `core_variable`
  MODIFY `variable_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Variable Id';
--
-- AUTO_INCREMENT for table `core_variable_value`
--
ALTER TABLE `core_variable_value`
  MODIFY `value_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Variable Value Id';
--
-- AUTO_INCREMENT for table `core_website`
--
ALTER TABLE `core_website`
  MODIFY `website_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Website Id', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `login_password`
--
ALTER TABLE `login_password`
  MODIFY `password_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `user_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;
--
-- AUTO_INCREMENT for table `mail_template`
--
ALTER TABLE `mail_template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `security_question`
--
ALTER TABLE `security_question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1290;
--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `role_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_security_answer`
--
ALTER TABLE `user_security_answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
