<?php

/**
 * @package Kronos
 * @version 1.0.0
 */
/*
Plugin Name: Kronos
Description: This plugin allows you to interconnect Kronos and Wordpress.
Author: Agoravita
Author URI: https://agoravita.com
Version: 1.0.0
*/

//! General
require 'general/index.php';

//! API
require 'api/rest-api.php';
require 'api/acf.php';
require 'api/auth.php';
require 'api/cache.php';
require 'api/e-commerce.php';
require 'api/formidable.php';
require 'api/cms.php';
require 'api/gutenberg.php';

//! Admin
require 'admin/tweaks.php';

//! Mail
require 'mail/mail.php';

//! Import
require 'import/import.php';

//! E-Commerce
// Payments gateway
require 'e-commerce/payments/monetico/monetico-cic-gateway.php';
require 'e-commerce/payments/account-pro/account-pro-gateway.php';
// Shipping methods
require 'e-commerce/shipping/dpd/dpd-method.php';
require 'e-commerce/shipping/pickup-store/pickup-store-method.php';

//! Plugins override
// ACF
require 'plugins/acf/class-acf-field-page_link.php';
require 'plugins/acf/class-acf-field-button-group.php';
require 'plugins/acf/class-acf-field-checkbox.php';
require 'plugins/acf/class-acf-field-radio.php';
require 'plugins/acf/class-acf-field-select.php';
require 'plugins/acf/class-acf-field-post_object.php';
require 'plugins/acf/class-acf-field-taxonomy.php';
