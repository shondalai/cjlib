<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class CjScript
{
	protected static $registry = array();
	
	private static $pluginsDir;

	/**
	 * Loads selected script/stylesheet. Valid options are: <br>
	 * <ul>
	 * <li>blockui</li>
	 * <li>bootbox</li>
	 * <li>chained</li>
	 * <li>chartjs</li>
	 * <li>checkbox</li>
	 * <li>colorbox</li>
	 * <li>colorpicker</li>
	 * <li>datetime</li>
	 * <li>filepond</li>
	 * <li>fontawesome</li>
	 * <li>form</li>
	 * <li>frappe</li>
	 * <li>gallery</li>
	 * <li>geosearch</li>
	 * <li>guillotine</li>
	 * <li>isinviewport</li>
	 * <li>imagegrid</li>
	 * <li>imagepicker</li>
	 * <li>jssocials</li>
	 * <li>locationpicker</li>
	 * <li>mathjax</li>
	 * <li>minput</li>
	 * <li>message</li>
	 * <li>noty</li>
	 * <li>pagination</li>
	 * <li>querybuilder</li>
	 * <li>raty</li>
	 * <li>rating</li>
	 * <li>scroll</li>
	 * <li>select</li>
	 * <li>slider</li>
	 * <li>signaturepad</li>
	 * <li>sortable</li>
	 * <li>sweetalert</li>
	 * <li>treeview</li>
	 * <li>validate</li>
	 * <li>videojs</li>
	 * </ul>
	 * 
	 * @param string $key
	 * @return boolean|mixed
	 */
	public static function _($key)
	{
		if (array_key_exists($key, static::$registry))
		{
			return false;
		}
		
		if(empty(self::$pluginsDir))
		{
		    self::$pluginsDir = JUri::root(true).'/media/com_cjlib/plugins';
		}

		// function name should be removed
		$args = func_get_args();
		array_shift($args);
		
		// call the function
		static::register($key, $key);
		$toCall = array('CjScript', $key);
		
		return static::call($toCall, $args);
	}
	
	protected static function call($function, $args)
	{
		if (!is_callable($function))
		{
			throw new InvalidArgumentException('Function not supported', 500);
		}
	
		// PHP 5.3 workaround
		$temp = array();
	
		foreach ($args as &$arg)
		{
			$temp[] = &$arg;
		}
	
		return call_user_func_array($function, $temp);
	}
	
	public static function register($key, $function)
	{
		if (is_callable($function))
		{
			static::$registry[$key] = $function;
	
			return true;
		}
	
		return false;
	}
	
	private static function bootbox($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $version = isset($options['bootstrap']) && $options['bootstrap'] == 2 ? 'v3.3.0' : 'v4.4.0';
	    static::addScript(self::$pluginsDir . '/bootbox/bootbox_'.$version.'.min.js', $custom);
	}

	private static function chained($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $remote = isset($options['remote']) ? $options['remote'] : false;
	    
	    if($remote)
	    {
	        static::addScript(self::$pluginsDir . '/chained/jquery.chained.remote.min.js', $custom);
	    }
	    else
	    {
	        static::addScript(self::$pluginsDir . '/chained/jquery.chained.min.js', $custom);
	    }
	}
	
	private static function chartjs($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/chartjs/chart.bundle.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/chartjs/chartjs-plugin-datalabels.min.js', $custom);
	    static::addStyleSheet(self::$pluginsDir . '/chartjs/chart.min.css', $custom);
	}
	
	private static function checkbox($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/checkbox/checkbox.min.css', $custom);
	}
	
	private static function colorbox($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/colorbox/colorbox.css', $custom);
	    static::addScript(self::$pluginsDir . '/colorbox/jquery.colorbox.min.js', $custom);
	}
	
	private static function colorpicker($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/colorpicker/themes/classic.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/colorpicker/pickr.min.js', $custom);
	}
	
	private static function datetime($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/datetime/datetimepicker.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/datetime/datetimepicker.full.min.js', $custom);
	}
	
	private static function filepond($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/filepond/filepond-plugin-image-preview.min.css', $custom);
	    static::addStyleSheet(self::$pluginsDir . '/filepond/filepond.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-polyfill.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-plugin-file-encode.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-plugin-file-validate-size.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-plugin-file-validate-type.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-plugin-image-preview.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond-plugin-image-exif-orientation.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/filepond/filepond.jquery.js', $custom);
	}

	private static function fontawesome($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $version = new JVersion();
	    
	    if (version_compare($version->getShortVersion(), '4.0', 'ge'))
	    {
	        Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'font-awesome.css', ['version' => 'auto', 'relative' => true]);
	    }
	    else
	    {
	        static::addStyleSheet(self::$pluginsDir . '/fontawesome/css/font-awesome.min.css', $custom);
	    }
	}

	private static function frappe($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/frappe/frappe-charts.min.iife.js', $custom);
	}
	
	private static function gallery($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/gallery/css/lightgallery.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/gallery/js/lightgallery-all.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/gallery/js/lg-deletebutton.js', $custom);
	}
	
	private static function guillotine($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/guillotine/jquery.guillotine.min.js', $custom);
	}
	
	private static function imagegrid($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/imagegrid/images-grid.css', $custom);
	    static::addScript(self::$pluginsDir . '/imagegrid/images-grid.js', $custom);
	}
	
	private static function imagepicker($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/imagepicker/image-picker.css', $custom);
	    static::addScript(self::$pluginsDir . '/imagepicker/image-picker.js', $custom);
	}
	
	private static function isinviewport($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/isinviewport/isInViewport.min.js', $custom);
	}
	
	private static function jssocials($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $theme	= isset($options['theme']) ? $options['theme'] : 'flat';
	    static::addStyleSheet(self::$pluginsDir . '/jssocials/jssocials.css', $custom);
	    static::addStyleSheet(self::$pluginsDir . '/jssocials/jssocials-theme-'.$theme.'.css', $custom);
	    static::addScript(self::$pluginsDir . '/jssocials/jssocials.min.js', $custom);
	}
	
	private static function locationpicker($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $googleMapsApiKey = isset($options['google_api_key']) ? $options['google_api_key'] : null;
	    
	    if(!empty($googleMapsApiKey))
	    {
	        static::addScript('https://maps.googleapis.com/maps/api/js?key='.$googleMapsApiKey.'&libraries=places', $custom);
	    }
	    
	    static::addScript(self::$pluginsDir . '/locationpicker/locationpicker.js', $custom);
	}
	
	private static function geosearch($options = null) {
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::leaflet($options);
	    
	    static::addStyleSheet(self::$pluginsDir . '/geosearch/geosearch.css', $custom);
	    static::addScript(self::$pluginsDir . '/geosearch/geosearch.umd.js', $custom);
	}
	
	private static function leaflet($options = null) {
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    
	    static::addStyleSheet(self::$pluginsDir . '/leaflet/leaflet.css', $custom);
	    static::addScript(self::$pluginsDir . '/leaflet/leaflet.js', $custom);
	}
	
	private static function minput($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/minput/mentions-input.css', $custom);
	    static::addScript(self::$pluginsDir . '/minput/underscore.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/minput/jq.elastic.js', $custom);
	    static::addScript(self::$pluginsDir . '/minput/mentions-input.js', $custom);
	}
	
	private static function noty($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/noty/noty.packaged.min.js', $custom);
	}
	
	private static function pagination($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/pagination/pagination.min.js', $custom);
	}
	
	private static function querybuilder($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/querybuilder/css/query-builder.default.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/querybuilder/js/query-builder.standalone.min.js', $custom);
	}
	
	private static function rating($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $suffix = isset($options['suffix']) ? $options['suffix'] : '';
	    $style = isset($options['style']) ? $options['style'] : 'fontawesome'.$suffix.'-stars-o';
	    
	    static::addStyleSheet(self::$pluginsDir . '/rating/themes/'.$style.'.css', $custom);
	    static::addScript(self::$pluginsDir . '/rating/barrating.min.js', $custom);
	}
	
	private static function raty($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/raty/jquery.raty.css', $custom);
	    static::addScript(self::$pluginsDir . '/raty/jquery.raty.js', $custom);
	}
	
	private static function scroll($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/scroll/infinite-scroll.pkgd.min.js', $custom);
	}
	
	private static function select($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $legacy = (isset($options['legacy']) && $options['legacy']) ? '/legacy' : '';
	    static::addStyleSheet(self::$pluginsDir . '/select'.$legacy.'/bootstrap-select.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/select'.$legacy.'/bootstrap-select.min.js', $custom, array('defer'=>true));
	}
	
	private static function signaturepad($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/signaturepad/signaturepad.min.js', $custom);
	}
	
	private static function slider($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $modern = isset($options['type']) ? $options['type'] : null;
	    
	    if($modern)
	    {
	        static::addScript(self::$pluginsDir . '/slider/ion.rangeSlider.min.js', $custom);
	        static::addStyleSheet(self::$pluginsDir . '/slider/ion.rangeSlider.min.css', $custom);
	    }
	    else
	    {
	        static::addScript(self::$pluginsDir . '/slider/nouislider.min.js', $custom);
	        static::addStyleSheet(self::$pluginsDir . '/slider/nouislider.min.css', $custom);
	    }
	}
	
	private static function sweetalert($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/sweetalert/sweetalert2.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/sweetalert/sweetalert2.min.js', $custom);
	}
	
	private static function treeview($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(self::$pluginsDir . '/treeview/jquery.treeview.js', $custom);
	}
	
	private static function validate($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $lang = JFactory::getLanguage()->getTag();
	    $locale = strstr($lang, '-', true);
	    
	    static::addScript(self::$pluginsDir . '/validation/jquery.validate.min.js', $custom);
	    
	    if($locale != 'en')
	    {
	        static::addScript(self::$pluginsDir . '/validation/localization/messages_'.$locale.'.js', $custom);
	    }
	}
	
	private static function videojs($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addStyleSheet(self::$pluginsDir . '/videojs/video-js.min.css', $custom);
	    static::addScript(self::$pluginsDir . '/videojs/video.min.js', $custom);
	    static::addScript(self::$pluginsDir . '/videojs/skin.min.js', $custom);
	    
	    if(isset($options['youtube']))
	    {
	        static::addScript(self::$pluginsDir . '/videojs/youtube.min.js', $custom);
	    }
	    
	    if(isset($options['vimeo']))
	    {
	        static::addScript(self::$pluginsDir . '/videojs/vimeo.js', $custom);
	    }
	    
	    if(isset($options['streaming']))
	    {
	        static::addScript(self::$pluginsDir . '/videojs/videojs-http-streaming.min.js', $custom);
	    }
	}
	
	private static function form($options = null)
	{
		$custom = isset($options['custom']) ? $options['custom'] : null;
		static::addScript(JUri::root(true).'/media/com_cjlib/jquery/jquery.form.min.js', $custom);
	}
	
	private static function message($options = null)
	{
		$custom = isset($options['custom']) ? $options['custom'] : null;
		static::addScript(JUri::root(true).'/media/com_cjlib/jquery/jquery.message.min.js', $custom);
		static::addStyleSheet(JUri::root(true).'/media/com_cjlib/jquery/jquery.message.css', $custom);
	}
	
	private static function sortable($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(JUri::root(true).'/media/com_cjlib/jquery/jquery-ui.sortable.min.js', $custom);
	}

	private static function blockui($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    static::addScript(JUri::root(true).'/media/com_cjlib/jquery/jquery.blockui.js', $custom);
	}
	
	private static function mathjax($options = null)
	{
	    $custom = isset($options['custom']) ? $options['custom'] : null;
	    $config = isset($options['config']) ? $options['config'] : 'TeX-MML-AM_CHTML';
	    static::addScript('https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config='.$config, $custom, array(), array('async'=>'async'));
	}
	
	private static function addStyleSheet($css, $custom = false)
	{
		$document = JFactory::getDocument();
		if(method_exists($document, 'addCustomTag') && $document->getType() != 'feed')
		{
			if($custom)
			{
				$document->addCustomTag('<link rel="stylesheet" href="'.$css.'" type="text/css" />');
			}
			else
			{
				$document->addStyleSheet($css);
			}
		}
	}
	
	private static function addScript($script, $custom = false, $options = array(), $attribs = array())
	{
		$document = JFactory::getDocument();
		if(method_exists($document, 'addCustomTag') && $document->getType() != 'feed')
		{
			if($custom)
			{
			    $defer = isset($options['defer']) ? 'defer' : '';
				$document->addCustomTag('<script src="'.$script.'" ' .$defer. ' type="text/javascript"></script>');
			}
			else
			{
				$document->addScript($script, $options, $attribs);
			}
		}
	}
	
	private static function addCustomTag($tag) {
	    $document = JFactory::getDocument();
	    if(method_exists($document, 'addCustomTag') && $document->getType() != 'feed')
	    {
	        $document->addCustomTag($tag);
	    }
	}
}
