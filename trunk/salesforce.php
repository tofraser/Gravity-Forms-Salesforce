<?php
/*
Plugin Name: Gravity Forms Salesforce Add-On
Description: Integrates <a href="http://katz.si/gf">Gravity Forms</a> with Salesforce, allowing form submissions to be automatically sent to your Salesforce account.
Version: 3.0.0-beta
Requires at least: 3.3
Author: Katz Web Services, Inc.
Author URI: https://katz.co

------------------------------------------------------------------------
Copyright 2014 Katz Web Services, Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

/**
 *
 * Load the loader...we need to do this to make sure that the Gravity Forms addon is supported.
 *
 */
class KWS_GF_Salesforce {

    const version = '3.0.0-beta';
    static $file;
    static $plugin_dir_path;

    function __construct() {

        self::$file = __FILE__;
        self::$plugin_dir_path = plugin_dir_path(__FILE__);

        add_action('plugins_loaded', array(&$this, 'load_files'), 100);

        add_action('admin_notices', array(&$this, 'addon_compatibility'));

        add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
    }

    function load_files() {

        if(!class_exists('KWSGFAddOn2_2')) {
            require_once(KWS_GF_Salesforce::$plugin_dir_path.'inc/kwsaddon.php');
        }

        if(!class_exists('KWS_GF_Salesforce_Loader')) {
            require_once(self::$plugin_dir_path.'inc/loader.php');
        }
    }

    /**
     * Add links next
     * @param  [type]      $links [description]
     * @param  [type]      $file  [description]
     * @return [type]             [description]
     */
    function plugin_action_links( $links, $file ) {
        if ( $file ==  plugin_basename(self::$file) ) {
            array_unshift( $links, '<a href="https://github.com/katzwebservices/Gravity-Forms-Salesforce/issues?state=open"><span class="dashicons dashicons-sos"></span>' . __('Support', 'idx-plus') . '</a>' );
            array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=gf_settings&amp;subview=Salesforce+Add-On' ) . '"><span class="dashicons dashicons-admin-generic"></span>' . __('Settings', 'idx-plus') . '</a>' );
        }
        return $links;
    }

    /**
     * Does the GF version support the GF Addon API?
     * @return void
     */
    function addon_compatibility() {

        // If the site has GF 1.7+, no notice necessary
        if(self::supports_addon_api()) { return; }

        $message = sprintf('%sGravity Forms Salesforce Add-On%s Requires version %s of Gravity Forms or better. If you have Gravity Forms, please upgrade to the latest Gravity Forms or purchase a license if you don&rsquo;t already have one. %sGet Gravity Forms<em> - starting at $39</em>%s', '<h3>', '</h3><p style="text-align:left;">', '1.7', '</p><p style="text-align:left;"><a href="http://katz.si/gravityforms" target="_blank" class="button button-secondary button-large button-hero">', '</a></p>');

        echo '<div class="updated">'.$message.'</div>';

    }

    /**
     * Does the current site have Gravity Forms that supports the Feed AddOn
     * @return boolean      True: has a good version of Gravity Forms; False: nope!
     */
    static public function supports_addon_api() {
        return class_exists('GFForms') && class_exists('GFFeedAddOn');
    }

}

new KWS_GF_Salesforce;