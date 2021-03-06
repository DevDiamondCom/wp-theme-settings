<?php
/**
 * Functions
 *
 * @author   DevDiamond <me@devdiamond.com>
 * @package  WP_Theme_Settings
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get option from WP_Theme_Settings Settings
 *
 * @param  string $option_slug  - Option slug
 * @param  string $option_name  - Option name
 * @param  bool   $default      - Default options
 *
 * @return mixed
 */
function wpts_get_option( $option_slug, $option_name = null, $default = false )
{
	return WPTS()->get_option( $option_slug, $option_name, $default );
}