<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined( '_JEXEC' ) or die;

class CjHtml {

	protected static $registry = [];

	public static function _( $key ) {
		// function name should be removed
		$args = func_get_args();
		array_shift( $args );

		// call the function
		$toCall = [ 'CjHtml', $key ];

		return static::call( $toCall, $args );
	}

	protected static function call( $function, $args ) {
		if ( ! is_callable( $function ) )
		{
			throw new InvalidArgumentException( 'Function not supported', 500 );
		}

		// PHP 5.3 workaround
		$temp = [];

		foreach ( $args as &$arg )
		{
			$temp[] = &$arg;
		}

		return call_user_func_array( $function, $temp );
	}

	private static function jssocials( $options = null ) {
		CjScript::_( 'jssocials', $options );
		$shares = '"email","twitter","facebook","linkedin","pinterest"';
		if ( ! empty( $options['shares'] ) )
		{
			if ( ! is_array( $options['shares'] ) )
			{
				$options['shares'] = explode( ',', $options['shares'] );
			}
			$shares = '"' . implode( '","', $options['shares'] ) . '"';
		}

		$content = 'jQuery(document).ready(function($){' .
		           'jsSocials.setDefaults("twitter", {logo: "fa fab fa-twitter"});' .
		           'jsSocials.setDefaults("facebook", {logo: "fa fab fa-facebook"});' .
		           'jsSocials.setDefaults("linkedin", {logo: "fa fab fa-linkedin"});' .
		           'jsSocials.setDefaults("pinterest", {logo: "fa fab fa-pinterest"});' .
		           '$("#cjshare").jsSocials({shares: [' . $shares . ']});});';
		Factory::getDocument()->addScriptDeclaration( $content );
		$size = isset( $options['size'] ) ? $options['size'] : 12;

		return '<div id="cjshare" style="display: inline-block; font-size: ' . $size . 'px;"></div>';
	}

}