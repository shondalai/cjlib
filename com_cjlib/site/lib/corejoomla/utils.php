<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\FilesystemHelper;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\SiteRouter;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Utilities\IpHelper;

defined( '_JEXEC' ) or die;

class CjLibUtils {

	private static $_router = null;

	/**
	 * word-sensitive substring function with html tags awareness
	 *
	 * @param   string text The text to cut
	 * @param   int len The maximum length of the cut string
	 * @param   array Array of tags to exclude
	 *
	 * @return string The modified html content
	 */
	public static function substrws( $text, $len = 180, $tags = [] ) {
		if ( function_exists( 'mb_strlen' ) )
		{
			if ( ( mb_strlen( $text, 'UTF-8' ) > $len ) )
			{
				$whitespaceposition = mb_strpos( $text, ' ', $len, 'UTF-8' ) - 1;
				if ( $whitespaceposition > 0 )
				{
					$chars = count_chars( mb_substr( $text, 0, $whitespaceposition + 1, 'UTF-8' ), 1 );
					if ( ! empty( $chars[ord( '<' )] ) && $chars[ord( '<' )] > $chars[ord( '>' )] )
					{
						$whitespaceposition = mb_strpos( $text, '>', $whitespaceposition, 'UTF-8' ) - 1;
					}

					$text = mb_substr( $text, 0, $whitespaceposition + 1, 'UTF-8' );
				}

				// close unclosed html tags
				if ( preg_match_all( "|<([a-zA-Z]+)|", $text, $aBuffer ) )
				{
					if ( ! empty( $aBuffer[1] ) )
					{
						preg_match_all( "|</([a-zA-Z]+)>|", $text, $aBuffer2 );
						if ( count( $aBuffer[1] ) != count( $aBuffer2[1] ) )
						{
							foreach ( $aBuffer[1] as $index => $tag )
							{
								if ( empty( $aBuffer2[1][$index] ) || $aBuffer2[1][$index] != $tag )
								{
									$text .= '</' . $tag . '>';
								}
							}
						}
					}
				}
			}
		}
		else
		{
			if ( ( strlen( $text ) > $len ) )
			{
				$whitespaceposition = strpos( $text, ' ', $len ) - 1;
				if ( $whitespaceposition > 0 )
				{

					$chars = count_chars( substr( $text, 0, $whitespaceposition + 1 ), 1 );
					if ( $chars[ord( '<' )] > $chars[ord( '>' )] )
					{
						$whitespaceposition = strpos( $text, '>', $whitespaceposition ) - 1;
					}

					$text = substr( $text, 0, $whitespaceposition + 1 );
				}

				// close unclosed html tags
				if ( preg_match_all( "|<([a-zA-Z]+)|", $text, $aBuffer ) )
				{
					if ( ! empty( $aBuffer[1] ) )
					{
						preg_match_all( "|</([a-zA-Z]+)>|", $text, $aBuffer2 );
						if ( count( $aBuffer[1] ) != count( $aBuffer2[1] ) )
						{
							foreach ( $aBuffer[1] as $index => $tag )
							{
								if ( empty( $aBuffer2[1][$index] ) || $aBuffer2[1][$index] != $tag )
								{
									$text .= '</' . $tag . '>';
								}
							}
						}
					}
				}
			}
		}

		return preg_replace( '#<p[^>]*>(\s|&nbsp;?)*</p>#', '', $text );
	}

	/**
	 * Convert special characters to HTML entities with UTF-8 encoding.
	 *
	 * @param   string  $var  content to be escaped
	 */
	public static function escape( $var ) {
		return ! empty( $var ) ? htmlspecialchars( $var, ENT_COMPAT, 'UTF-8' ) : null;
	}

	/**
	 * Returns unicode alias string from the <code>title</code> passed as an argument. If the Joomla version is less than 1.6, the function will gracefully degrades and outputs
	 * normal alias.
	 *
	 * @param   string  $title
	 */
	public static function getUrlSafeString( $title ) {
		if ( Factory::getConfig()->get( 'unicodeslugs' ) == 1 )
		{
			return OutputFilter::stringURLUnicodeSlug( $title );
		}
		else
		{
			return OutputFilter::stringURLSafe( $title );
		}
	}

	/**
	 * Gets the ip address of the user from request
	 *
	 * @return string ip address
	 * @deprecated use <code>Joomla\Utilities\IpHelper::getIp()</code>
	 */
	public static function getUserIpAddress() {
		$version = new Version();
		if ( version_compare( $version->getShortVersion(), '3.9', 'lt' ) )
		{
			$ip = '';

			if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and strlen( $_SERVER['HTTP_X_FORWARDED_FOR'] ) > 6 )
			{

				$ip = strip_tags( $_SERVER['HTTP_X_FORWARDED_FOR'] );
			}
			elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) and strlen( $_SERVER['HTTP_CLIENT_IP'] ) > 6 )
			{

				$ip = strip_tags( $_SERVER['HTTP_CLIENT_IP'] );
			}
			elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) and strlen( $_SERVER['REMOTE_ADDR'] ) > 6 )
			{

				$ip = strip_tags( $_SERVER['REMOTE_ADDR'] );
			}
			$ip = explode( ',', $ip );
			$ip = $ip[0];

			return trim( $ip );
		}
		else
		{
			IpHelper::setAllowIpOverrides( true );

			return IpHelper::getIp();
		}
	}

	/**
	 * Gets the formatted number in the format 10, 100, 1000, 10k, 20.1k etc
	 *
	 * @param   integer  $num  number to format
	 *
	 * @return string formatted number
	 */
	public static function formatNumber( $num ) {
		$num = (int) $num;
		if ( $num < 1000 )
		{
			return $num;
		}
		elseif ( $num < 10000 )
		{
			return round( $num / 1000, 2 ) . 'k';//substr($num, 0, 1).','.substr($num, 1);
		}
		elseif ( $num < 1000000 )
		{
			return round( $num / 1000, 1 ) . 'k';
		}
		else
		{
			return round( $num / 1000000, 2 ) . 'm';
		}
	}


	/**
	 * Generate a random character string
	 *
	 * @param   int     $length  length of the string to be generated
	 * @param   string  $chars   characters to be considered, default alphanumeric characters.
	 *
	 * @return string randomly generated string
	 */
	public static function getRandomKey( $length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890' ) {

		// Length of character list
		$chars_length = ( strlen( $chars ) - 1 );

		// Start our string
		$string = $chars[rand( 0, $chars_length )];

		// Generate random string
		for ( $i = 1; $i < $length; $i = strlen( $string ) )
		{
			// Grab a random character from our list
			$r = $chars[rand( 0, $chars_length )];

			// Make sure the same two characters don't appear next to each other
			if ( $r != $string[$i - 1] )
			{
				$string .= $r;
			}
		}

		// Return the string
		return $string;
	}

	/**
	 * Gets the current url
	 *
	 * @return string
	 */
	public static function getCurrentUrl() {
		$absUrl = Uri::getInstance()->toString();

		return Route::_( $absUrl );
	}

	/**
	 * Uploads the form input file to specified directory, optionally with a specified name. If no target directory specified, the media directory will be used.
	 *
	 * @param   string   $inputName    name of the file input field
	 * @param   unknown  $targetDir    absolute path of the target directory where the file will be stored, do not end with /. Media directory will be used if left null
	 * @param   unknown  $targetName   target file name. Randomly generated name will be used if null
	 * @param   array    $allowedExts  comma separated list of allowd file extensions
	 * @param   number   $maxSize      maximum allowed size of the file in bytes. If not set, default system value will be used
	 *
	 * @return boolean|string name of the file if successful, false otherwise
	 * @throws Exception if any validation fails
	 *
	 */
	public static function uploadFile( $inputName = 'uploadfile', $targetDir = null, $targetName = null, $allowedExts = [], $maxSize = 0 ) {
		$input   = Factory::getApplication()->input;
		$tmpFile = $input->files->get( $inputName );

		if ( $tmpFile['error'] > 0 )
		{
			throw new Exception( Text::_( 'JERROR_AN_ERROR_HAS_OCCURRED' ) . '| RC=101', 500 );
		}

		if ( empty( $tmpFile ) )
		{
			return false;
		}

		$tmpPath  = $tmpFile['tmp_name'];
		$tmpName  = $tmpFile['name'];
		$fileSize = (float) $tmpFile['size'];
		$tmpExt   = strtolower( File::getExt( $tmpName ) );

		if ( ! empty( $allowedExts ) )
		{
			$extns = explode( ',', $allowedExts );
			if ( ! in_array( $tmpExt, $extns ) )
			{
				throw new Exception( Text::_( 'COM_CJLIB_ERROR_INVALID_FILE_TYPE' ) . '| RC=102', 403 );
			}
		}

		$maxSize = $maxSize ? $maxSize : (float) FilesystemHelper::fileUploadMaxSize( false );
		if ( $fileSize == 0 || $fileSize > $maxSize )
		{
			throw new Exception( Text::_( 'COM_CJLIB_ERROR_MAX_SIZE_FAILURE' ) . '| RC=3', 403 );
		}

		$fileName = $targetName ?: CjLibUtils::getRandomKey( 25, 'abcdefghijklmnopqrstuvwxyz1234567890' ) . '.' . $tmpExt;
		$fileDir  = $targetDir ?: JPATH_ROOT . '/media';

		if ( File::upload( $tmpPath, $fileDir . '/' . $fileName ) )
		{
			return $fileName;
		}

		return false;
	}

	public static function getCategoryOptions( $extension, $acl = false, $published = [ 0, 1 ] ) {
		$options = [];
		$jinput  = Factory::getApplication()->input;
		$oldCat  = $jinput->get( 'id', 0 );

		$db    = Factory::getDbo();
		$query = $db->getQuery( true )
		            ->select( 'DISTINCT a.id AS value, a.title AS text, a.level, a.published, a.lft, a.language' );

		$subQuery = $db->getQuery( true )
		               ->select( 'id,title,level,published,parent_id,extension,lft,rgt,language' )
		               ->from( '#__categories' )
		               ->where( '(extension = ' . $db->quote( $extension ) . ')' )
		               ->where( 'published IN (' . implode( ',', $published ) . ')' );

		// Filter language
		$languages = [ Factory::getLanguage()->getTag(), '*' ];
		$subQuery->where( 'language IN (' . implode( ',', $db->quote( $languages ) ) . ')' );

		$query
			->from( '(' . $subQuery->__toString() . ') AS a' )
			->join( 'LEFT', $db->quoteName( '#__categories' ) . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt' )
			->order( 'a.lft ASC' );

		// Get the options.
		$db->setQuery( $query );

		try
		{
			$options = $db->loadObjectList();
		}
		catch ( RuntimeException $e )
		{
			throw new Exception( $e->getMessage(), 500 );
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ( $i = 0, $n = count( $options ); $i < $n; $i ++ )
		{
			if ( $options[$i]->published == 1 )
			{
				$options[$i]->text = str_repeat( '- ', $options[$i]->level ) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat( '- ', $options[$i]->level ) . '[' . $options[$i]->text . ']';
			}
		}

		// Get the current user object.
		$user = Factory::getUser();
		if ( $acl )
		{
			foreach ( $options as $i => $option )
			{
				if ( $user->authorise( $acl, $extension . '.category.' . $option->value ) != true && $option->level != 0 )
				{
					unset( $options[$i] );
				}
			}
		}
		else// For new items we want a list of categories you are allowed to create in.
			if ( $oldCat == 0 )
			{
				foreach ( $options as $i => $option )
				{
					/* To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ( $user->authorise( 'core.create', $extension . '.category.' . $option->value ) != true && $option->level != 0 )
					{
						unset( $options[$i] );
					}
				}
			}
			// If you have an existing category id things are more complex.
			else
			{
				/* If you are only allowed to edit in this category but not edit.state, you should not get any
				 * option to change the category parent for a category or the category for a content item,
				 * but you should be able to save in that category.
				 */
				foreach ( $options as $i => $option )
				{
					if ( $user->authorise( 'core.edit.state', $extension . '.category.' . $oldCat ) != true && ! isset( $oldParent ) )
					{
						if ( $option->value != $oldCat )
						{
							unset( $options[$i] );
						}
					}

					if ( $user->authorise( 'core.edit.state', $extension . '.category.' . $oldCat ) != true
					     && ( isset( $oldParent ) )
					     && $option->value != $oldParent )
					{
						unset( $options[$i] );
					}

					// However, if you can edit.state you can also move this to another category for which you have
					// create permission and you should also still be able to save in the current category.
					if ( ( $user->authorise( 'core.create', $extension . '.category.' . $option->value ) != true )
					     && ( $option->value != $oldCat && ! isset( $oldParent ) ) )
					{
						{
							unset( $options[$i] );
						}
					}

					if ( ( $user->authorise( 'core.create', $extension . '.category.' . $option->value ) != true )
					     && ( isset( $oldParent ) )
					     && $option->value != $oldParent )
					{
						{
							unset( $options[$i] );
						}
					}
				}
			}

		return $options;
	}

	public static function getUserLocation( $ip ) {
		$info                   = [];
		$info['continent']      = 'Unknown';
		$info['country']        = "Unknown";
		$info['country_code']   = "XX";
		$info['country_code_3'] = "XXX";
		$info['city']           = "Unknown";
		$info['lattitude']      = '';
		$info['longitude']      = '';

		if ( ! file_exists( JPATH_ROOT . '/media/com_cjlib/geoip/GeoLite2-City.mmdb' ) )
		{
			return $info;
		}

		try
		{
			$reader = new GeoIp2\Database\Reader( JPATH_ROOT . '/media/com_cjlib/geoip/GeoLite2-City.mmdb' );
			$record = $reader->city( $ip );

			if ( $record )
			{
				$info['country']        = $record->country->name;
				$info['country_code']   = $record->country->isoCode;
				$info['country_code_3'] = $record->country->isoCode;
				$info['city']           = $record->city->name;
				$info['lattitude']      = $record->location->latitude;
				$info['longitude']      = $record->location->longitude;
			}
		}
		catch ( Exception $e )
		{
			$user = Factory::getUser();
			if ( $user->authorise( 'core.admin', 'com_communitysurveys' ) )
			{
				Factory::getApplication()->enqueueMessage( $e->getMessage() );
			}
		}

		return $info;
	}

	public static function buildSefUrl( $url, $xhtml = true, $ssl = null ) {
		require self::buildSefSurveyUrl( $url, $xhtml, $ssl );
	}

	public static function buildSefSurveyUrl( $url, $xhtml = true, $ssl = null ) {
		if ( ! self::$_router )
		{
			// Get the router.
			self::$_router = Factory::getContainer()->get(SiteRouter::class);

			// Make sure that we have our router
			if ( ! self::$_router )
			{
				return null;
			}
		}

		if ( ! is_array( $url ) && ( strpos( $url, '&' ) !== 0 ) && ( strpos( $url, 'index.php' ) !== 0 ) )
		{
			return $url;
		}

		// Build route.
		$uri = self::$_router->build( $url );
		$scheme = [ 'path', 'query', 'fragment' ];

		/*
		 * Get the secure/unsecure URLs.
		 *
		 * If the first 5 characters of the BASE are 'https', then we are on an ssl connection over
		 * https and need to set our secure URL to the current request URL, if not, and the scheme is
		 * 'http', then we need to do a quick string manipulation to switch schemes.
		 */
		if ( (int) $ssl || $uri->isSSL() )
		{
			static $host_port;

			if ( ! is_array( $host_port ) )
			{
				$uri2      = Uri::getInstance();
				$host_port = [ $uri2->getHost(), $uri2->getPort() ];
			}

			// Determine which scheme we want.
			$uri->setScheme( ( (int) $ssl === 1 || $uri->isSSL() ) ? 'https' : 'http' );
			$uri->setHost( $host_port[0] );
			$uri->setPort( $host_port[1] );
			$scheme = array_merge( $scheme, [ 'host', 'port', 'scheme' ] );
		}

		$url = $uri->toString( $scheme );

		// Replace spaces.
		$url = preg_replace( '/\s/u', '%20', $url );

		if ( $xhtml )
		{
			$url = htmlspecialchars( $url );
		}

		$url = str_replace( '/administrator/', '/', $url );

		return $url;
	}

	/**
	 * @return boolean isSite
	 * @deprecated use $app->isClient('site')
	 *
	 */
	public static function isSite() {
		$version = new Version();
		$app     = Factory::getApplication();

		if ( version_compare( $version->getShortVersion(), '3.7', 'lt' ) )
		{
			return $app->isSite();
		}
		else
		{
			return $app->isClient( 'site' );
		}
	}

}
