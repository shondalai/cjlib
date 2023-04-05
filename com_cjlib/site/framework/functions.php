<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2018 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\String\StringHelper;
use Joomla\Archive\Archive;

require_once 'api.php';

class CJFunctions {

	private static $_country_names = array();
	
	/**
	 * Generates the pagination, given the non-Sef Joomla url (with itemid), start page number, current page number, total number of pages and number of rows per page.
	 *
	 * @param string $nonSefUrl
	 * @param int $start
	 * @param int $current
	 * @param int $total
	 * @param int $count
	 */
	public static function get_pagination($nonSefUrl, $start, $current, $total, $count=20, $boostrap = false){

		$content = '';
		
		if($total == 1) return $content;
		
		if($boostrap){

			$content = $content.'<div class="cleafix">';
			$content = $content.'<div class="pull-right">'.sprintf(JText::_('COM_CJLIB_PAGINATION_PAGES_COUNT'), $current, $total).'</div>';
			$content = $content.'<div class="pagination pagination-small hidden-phone hidden-sm hidden-xs"><ul>';
			
			if( $total > 0 ){
				
				if($current == 1){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" title="'.JText::_('COM_CJLIB_PAGINATION_START').'" class="tooltip-hover"><i class="icon-step-backward"></i></a>
							</li>';
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover"><i class="icon-backward"></i></a>
							</li>';
				} else {
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start=0' ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_START').'" class="tooltip-hover"><i class="icon-step-backward"></i></a>
							</li>';
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.( ($current - 2)*$count )).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover"><i class="icon-backward"></i></a>
							</li>';
				}
				
				for($i = $start; $i <= $total && $i <= $start + 9; $i++){
					
					$url = JRoute::_( $nonSefUrl.'&start='.( ( $i - 1 ) * $count ) );
					
					if($i == $current){
						
						$content = $content.'<li class="active"><a class="current" href="'.$url.'">'.$i.'</a></li>';
					} else {
						
						$content = $content.'<li><a href="'.$url.'">'.$i.'</a></li>';
					}
				}
				
				if($current == $total){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" onclick="return false;" class="tooltip-hover"><i class="icon-forward"></i></a>
							</li>';
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'" onclick="return false;" class="tooltip-hover"><i class="icon-step-forward"></i></a>
							</li>';
				}else{
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.($current * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" class="tooltip-hover"><i class="icon-forward"></i></a>
							</li>';
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.(($total - 1) * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'" class="tooltip-hover"><i class="icon-step-forward"></i></a>
							</li>';
				}
			}
			
			$content = $content.'</ul></div>';
			
			$content = $content.'<div class="pagination visible-phone visible-sm visible-xs">';
			$content = $content.'<ul class="paginationpager">';
			if( $total > 0 ){
				
				if($current == 1){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</i></a>
							</li>';
				} else {
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.( ($current - 2)*$count )).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</a>
							</li>';
				}
				
				if($current == $total){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" onclick="return false;" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>
							</li>';
				}else{
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.($current * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>
							</li>';
				}
			}
			$content = $content.'</ul></div></div>';
		} else {
			
			$content = $content.'<div class="cjpagination">';
			$content = $content.'<div class="float-right">'.sprintf(JText::_('COM_CJLIB_PAGINATION_PAGES_COUNT'), $current, $total).'</div>';
	
			if( $total > 0 ){
	
				$first_disabled = ( $current == 1 ) ? ' disabled' : '';
				$last_disabled = ( $current == $total ) ? ' disabled' : ''; 
				
				$content = $content.'<div class="page-main">';
				$content = $content.'<a class="first'.$first_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start=0' ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_START').'">'.JText::_('COM_CJLIB_PAGINATION_START').'</a>';
				$content = $content.'<a class="previous'.$first_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( $current > 1 ? ( $current - 2 ) * $count : 0 ) ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</a>';
	
				for($i = $start; $i <= $total && $i < $start + 10; $i++){
	
					$url = JRoute::_( $nonSefUrl.'&start='.( ( $i - 1 ) * $count ) );
	
					if($i == $current){
	
						$content = $content.'<a class="current" href="'.$url.'">'.$i.'</a>';
					} else{
	
						$content = $content.'<a href="'.$url.'">'.$i.'</a>';
					}
				}
	
				$content = $content.'<a class="next'.$last_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( $current < $total ? $current * $count : 0 ) ).'"
										title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>';
				$content = $content.'<a class="last'.$last_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( ( $total - 1 ) * $count ) ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'">'.JText::_('COM_CJLIB_PAGINATION_LAST').'</a>';
				$content = $content.'</div>';
			}
	
			$content = $content.'<div class="clear"></div>';
			$content = $content.'</div>';
		}

		return $content;
	}
	
	/**
	 * Loads the jquery library and set of jquery plugins passed as parameters to the function.
	 *
	 * The required jquery plugins should be passed as associative array of names with name as libs
	 * Ex:
	 * <code>
	 * $params = array('libs'=>array('ui', 'form', 'validate', 'treeview', 'menu', 'waypoints', 'tags', 'inlinelabel', 'scrollto'), 'theme'=>'start');
	 * CJFunctions::load_jquery($params);
	 * </code>
	 *
	 * @param array $params
	 */
	public static function load_jquery($params=array()){

		$app = JFactory::getApplication();
		$document = JFactory::getDocument();

		$plugins = !empty($app->jqueryplugins) ? $app->jqueryplugins : array();
		$custom_tag = isset($params['custom_tag']) ? true : false;

		if(!in_array('baseloc', $plugins)){
				
			$document->addScriptDeclaration('var cjlib_loc = "'.CJLIB_URI.'";');
			$plugins[] = 'baseloc';
		}
		
		JHtml::_('jquery.framework');
		
		foreach ($params['libs'] as $plugin)
		{
		    if(in_array($plugin, $plugins))
		    {
		        continue;
		    }
		    
		    switch ($plugin)
		    {
		        case 'ui':
		            JHtml::_('jquery.ui', array('core', 'sortable'));
		            break;
		            
		        case 'json':
		            CJFunctions::add_script_to_document($document, 'json2.js', $custom_tag);
		            break;
		            
                case 'social':
                    $document->addStyleSheet(CJLIB_URI.'/jquery/social/socialcount-with-icons.min.css');
                    CJFunctions::add_script_to_document($document, 'socialcount.min.js', $custom_tag, CJLIB_URI.'/jquery/social/');
                    break;
                    
                case 'chosen2':
                    $document->addStyleSheet(JUri::root(true).'/media/jui/css/chosen.css');
                    $document->addScript(JUri::root(true).'/media/system/js/core.js');
                    $document->addScript(JUri::root(true).'/media/jui/js/chosen.jquery.js');
                    break;
                    
                case 'chosen':
                    $document->addStyleSheet(JUri::root(true).'/media/jui/css/chosen.css');
                    $document->addScript(JUri::root(true).'/media/system/js/core.js');
                    $document->addScript(JUri::root(true).'/media/jui/js/chosen.jquery.js');
                    $document->addScript(JUri::root(true).'/media/jui/js/ajax-chosen.min.js');
                    $document->addScript(CJLIB_URI.'/jquery/chosen/cj.chosentags.js');
                    break;
                    
                case 'chosentags':
                    $document->addScript(JUri::root(true).'/media/jui/js/ajax-chosen.min.js');
                    $document->addScript(CJLIB_URI.'/jquery/chosen/cj.chosentags.js');
                    break;
                    
                case 'tags':
                    $document->addStyleSheet(CJLIB_URI.'/jquery/cj.tags.min.css');
                    CJFunctions::add_script_to_document($document, 'cj.tags.js', $custom_tag);
                    break;
                    
                case 'backbone':
                    CJFunctions::add_script_to_document($document, 'underscore-min.js', $custom_tag);
                    CJFunctions::add_script_to_document($document, 'backbone-min.js', $custom_tag);
                    break;
                    
                case 'bootstrap':
                    JHtml::_('bootstrap.framework');
                    CjFunctions::add_css_to_document($document, CJLIB_MEDIA_URI.'/bootstrap/css/bootstrap.min.css', $custom_tag);
                    $document->addScriptDeclaration('<!--[if lt IE 9]><script type="text/javascript" src="'.CJLIB_MEDIA_URI.'/bootstrap/js/respond.min.js"><![endif]-->');
                    CJLib::$_bootstrap_loaded = true;
                    break;
                    
                case 'dygraph':
                    CJFunctions::add_script(CJLIB_MEDIA_URI.'/jquery/dygraph-combined.js', $custom_tag);
                    break;
                    
                default:
                    CjScript::_($plugin, array('custom'=>$custom_tag));
                    break;
		    }

			$app->jqueryplugins = $plugins;
		}
	}
	
	public static function add_script_to_document($doc, $script, $custom_tag, $base = null)
	{
		$base = !$base ? CJLIB_URI.'/jquery/' : $base;
		if(method_exists($doc, 'addCustomTag') && $doc->getType() != 'feed') 
		{
			if($custom_tag)
			{
				$doc->addCustomTag('<script src="'.$base.$script.'" type="text/javascript"></script>');
			} 
			else 
			{
				$doc->addScript($base.$script);
			}
		}
	}
	
	public static function add_script($script, $custom_tag)
	{
		$doc = JFactory::getDocument();
		if(method_exists($doc, 'addCustomTag') && $doc->getType() != 'feed') 
		{
			if($custom_tag)
			{
				$doc->addCustomTag('<script src="'.$script.'" type="text/javascript"></script>');
			} 
			else 
			{
				$doc->addScript($script);
			}
		}
	}

	public static function add_css_to_document($doc, $css, $custom_tag)
	{
		if(method_exists($doc, 'addCustomTag') && $doc->getType() != 'feed') 
		{
			if($custom_tag)
			{
				$doc->addCustomTag('<link rel="stylesheet" href="'.$css.'" type="text/css" />');
			} 
			else 
			{
				$doc->addStyleSheet($css);
			}
		}
	}
	
	/**
	 * Returns the editor html markup based on the <code>editor</code> type choosen, bbcode - BBCode Editor, wysiwyg - Joomla default editor, none - plain text area.
	 *  
	 * @param string $editor editor type
	 * @param int $id id of the editor/textarea tag
	 * @param string $name name of the editor/textarea tag
	 * @param string $html default content to be populated in editor/textarea
	 * @param int $rows number of rows of the textarea
	 * @param int $cols number of columns of the textarea
	 * @param string $width width of the editor in pixels or percentage  
	 * @param string $height height of the editor in pixels or percentage
	 * @param string $class css class applied to the editor 
	 * @param string $style style applied to the editor
	 * 
	 * @return string output of the loaded editor markup 
	 */
	public static function load_editor($editor, $id, $name, $html, $rows, $cols, $width=null, $height=null, $class=null, $style=null, $custom_tag = false){
	
		$style = $style ? ' style="'.$style.'"' : '';
		$class = $class ? ' class="'.$class.'"' : '';
		$width = $width ? $width : '450px';
		$height = $height ? $height : '200px';
	
		if($editor == 'bbcode') {
				
			$content = '<style type="text/css"><!-- .markItUpHeader ul { margin: 0; } .markItUpHeader ul li	{ list-style:none; float:left; position:relative; background: none;	line-height: 100%; margin: 0; padding: 0; } --></style>';
			$content .= '<div style="width: '.$width.';"><textarea name="'.$name.'" id="'.$id.'" rows="'.$rows.'" cols="'.$cols.'"'.$style.$class.'>'.$html.'</textarea></div>';
				
			$document = JFactory::getDocument();

			CJFunctions::add_script_to_document($document, 'jquery.markitup.js', $custom_tag, CJLIB_URI.'/lib/markitup/');
			CJFunctions::add_script_to_document($document, 'set.js', $custom_tag, CJLIB_URI.'/lib/markitup/sets/bbcode/');
				
			$document->addStyleSheet(CJLIB_URI.'/lib/markitup/skins/markitup/style.css');
			$document->addStyleSheet(CJLIB_URI.'/lib/markitup/sets/bbcode/style.css');
				
			$document->addScriptDeclaration('jQuery(document).ready(function($){$("#'.$id.'").markItUp(cjbbcode)});;');
		} else if($editor == 'wysiwyg' || $editor == 'default') {
				
			$jeditor = JFactory::getEditor();
			$content = '<div style="overflow: hidden; clear: both;">'.$jeditor->display( $name, $html, $width, $height, $cols, $rows, true, $id ).'</div>';
		}else if($editor == 'wysiwygbb'){
			
			$document = JFactory::getDocument();
			CJFunctions::add_css_to_document($document, CJLIB_MEDIA_URI.'/sceditor/minified/themes/square.min.css', $custom_tag);
			CJFunctions::add_script(CJLIB_MEDIA_URI.'/sceditor/minified/jquery.sceditor.bbcode.min.js', $custom_tag);
			
			$document->addCustomTag('
					<script type="text/javascript">
					jQuery(document).ready(function($){
						$("#'.$id.'").sceditor({
							plugins: "bbcode", 
							style: "'.JUri::root(true).'/media/com_cjlib/sceditor/minified/jquery.sceditor.default.min.css",
							emoticonsRoot: "'.JUri::root(true).'/media/com_cjlib/sceditor/",
							width: "98%",
							autoUpdate: true
						});
						$("#'.$id.'").sceditor("instance").rtl('.($document->direction == 'rtl' ? 'true' : 'false').');
					});
					</script>');
			$content = '<textarea name="'.$name.'" id="'.$id.'" rows="5" cols="50"'.$style.$class.'>'.$html.'</textarea>';
		} else {
			
			$content = '<textarea name="'.$name.'" id="'.$id.'" rows="5" cols="50"'.$style.$class.'>'.$html.'</textarea>';
		}
	
		return $content;
	}
	
	/**
	 * Processes BBCode content if the <code>bbcode</code> value is set, else returns <code>content</code>
	 * 
	 * @param string $content html or bbcode content
	 * @param boolean $bbcode flag indicating the content type is bbocde or not 
	 * @param boolean $process_content_plugins flag to enable processing of Joomla(r) content plugins
	 */
	public static function process_html($content, $bbcode = false, $process_content_plugins = false, $autolink = true){
	
		if($bbcode){
				
			if(!function_exists('BBCode2Html')){
	
				require_once CJLIB_PATH.'/lib/markitup/bbcodeparser.php';
			}
				
			$content = BBCode2Html($content);
		}

		if($autolink){
			
			require_once CJLIB_PATH. '/lib/misc/lib_autolink.php';
			$content = autolink($content, 50, ' rel="nofollow"');
		}
				
		if($process_content_plugins){

			$content = JHTML::_('content.prepare', $content);
		}
		
		return $content;
	}
	
	public static function parse_html($content, $process_content_plugins = false, $bbcode = true, $autolink = true){
		
		if($bbcode){
			
			require_once CJLIB_PATH.'/lib/nbbc/nbbc_main.php';
			$bbcode = new BBCode();
	
			$bbcode->SetSmileyURL(CJLIB_MEDIA_URI.'/smileys');
			$bbcode->SetSmileyDir(CJLIB_MEDIA_PATH.'/smileys');

			$bbcode->SetTagMarker('[');
			$bbcode->SetAllowAmpersand(false);
			$bbcode->SetEnableSmileys(true);
			$bbcode->SetDetectURLs($autolink);
			$bbcode->SetPlainMode(false);
			$bbcode->SetDebug(false);
			
			$content = $bbcode->Parse($content);
		}else if($autolink){
			
			require_once CJLIB_PATH . '/lib/misc/lib_autolink.php';
			$content = autolink($content, 50, ' rel="nofollow"');
		}
		
		if($process_content_plugins){
		
			$content = JHTML::_('content.prepare', $content);
		}
		
		return $content;
	}
	
	public static function preprocessHtml($content, $plugins = false, $bbcode = false, $autolink = false)
	{
		if($bbcode)
		{
			require_once CJLIB_PATH.'/lib/jbbcode/Parser.php';
			require_once CJLIB_PATH.'/lib/jbbcode/custom/CjCustomCodeDefinitions.php';
			
			$parser = new JBBCode\Parser();
			$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
			$parser->addCodeDefinitionSet(new JBBCode\CjCodeDefinitionSet());
			
			$content = $parser->parse(nl2br(htmlspecialchars($content, ENT_COMPAT, 'UTF-8')))->getAsHtml();
		}
		
		if($autolink)
		{
			require_once CJLIB_PATH . '/lib/misc/lib_autolink.php';
			$content = autolink($content, 50, ' rel="nofollow"');
		}
		
		if($plugins)
		{
			$content = JHTML::_('content.prepare', $content);
		}
		
		return $content;
	}
	
	/**
	 * Function to get available theme names from jquery ui library
	 *
	 * @return array list of theme names available on jquery ui library
	 */
	public static function get_ui_theme_names(){

		$themes = array();
		$path = CJLIB_PATH.'/jquery/themes';

		if(file_exists($path)){
				
			$themes = JFolder::folders($path);
		}

		return $themes;
	}

	/**
	 * Gets the user avatar of the selected <code>system</code>
	 * 
	 * @param string $system Avatar system to be used
	 * @param int $userid user id
	 * @param string $username username or name
	 * @param int $height height of the avatar
	 * 
	 * @return string user avatar
	 * @deprecated use CjLibApi()->getUserAvatar
	 */
	public static function get_user_avatar($system, $userid, $displayname = 'username', $height = 48, $email = null, $attribs = array(), $img_attribs = array())
	{
		$api = new CjLibApi();
		return $api->getUserAvatar($system, $system, $userid, $displayname, $height, $email, $attribs, $img_attribs);
	}
	
	/**
	 * Gets the user avatar image without any link
	 * 
	 * @param unknown $system
	 * @param unknown $userid
	 * @param unknown $alt
	 * @param number $height
	 * @param string $email
	 * @param string $path_only
	 * @param unknown $attribs
	 * @return void|Ambigous <string, unknown, NULL, string>
	 * 
	 * @deprecated use CjLibApi()->get_user_avatar_image
	 */
	public static function get_user_avatar_image($system, $userid, $alt, $height = 48, $email = null, $path_only = true, $attribs = array())
	{
		$api = new CjLibApi();
		return $api->getUserAvatarImage($system, $userid, $email, $height, $path_only, $alt, $attribs);
	}
	
	/**
	 * @deprecated use CjLibApi()->prefetchUserProfiles
	 * @param unknown $system
	 * @param unknown $ids
	 */
	public static function load_users($system, $ids)
	{
		$api = new CjLibApi();
		$api->prefetchUserProfiles($system, $ids);
	}
	
	/**
	 * Gets the user profile url of selected <code>system</code>. Currently supported systems are <br><br> 
	 * 
	 * JomSocial - jomsocial, Community Builder - cb, Touch - touch, Kunena - kunena, Alpha User Points - aup
	 * 
	 * @param string $system User profile system
	 * @param int $userid user id
	 * @param string $username User name to be used to display with link
	 * @param array $links array of links for mighty touch
	 * @param path_only boolean want to retrive just the url or the full html hyperlink markup?
	 * 
	 * @return string user profile url
	 * @deprecated use CjLibApi()->getUserProfileUrl
	 */
	public static function get_user_profile_url($system, $userid = 0, $username = 'Guest', $path_only = true, $attribs = array())
	{
		$api = new CjLibApi();
		return $api->getUserProfileUrl($system, $userid, $path_only, $username, $attribs);
	}
	
	/**
	 * deprecated, use CjLibApi()->getUserProfileUrl instead
	 * @deprecated
	 */
	public static function get_user_profile_link($system, $userid = 0, $username = 'Guest', $links = array(), $alias = null, $path_only = false, $attribs = array()){
		
		return CJFunctions::get_user_profile_url($system, $userid, $username, $path_only, $attribs);
	}
	
	/**
	 * deprecated, use CjLibDateUtils::getLocalizedDate instead
	 * @deprecated
	 */
	public static function get_localized_date($strdate, $format = 'Y-m-d'){
		
		return CjLibDateUtils::getLocalizedDate($strdate, $format);
	}
	
	/**
	 * Gets the human friendly date string from a date
	 * 
	 * @param string $strdate date
	 * 
	 * @return string formatted date string
	 * @deprecated use CjLibUtils::getHumanReadableDate
	 */
	public static function get_formatted_date($strdate) {
		
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/dateutils.php';
		return CjLibDateUtils::getHumanReadableDate($strdate);
	}
	
	/**
	 * Gets the difference between two dates in human readable format.
	 * 
	 * @param string $date1
	 * @param string $date2
	 */
	public static function get_date_difference($strdate1, $strdate2){
		
		$diff = strtotime($strdate2) - strtotime($strdate1);
		$days = floor($diff / 86400);
		$hours = floor(($diff % 86400) / 3600);
		$minutes = floor(($diff % 3600) / 60);
		$seconds = floor($diff % 60);
		
		if($diff <= 0){
			
			return JText::_('COM_CJLIB_NOT_COMPLETED');
		}else if($days > 0) {
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_DAYS', $days, $hours, $minutes, $seconds);
		} else if($hours > 0){
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_HOURS', $hours, $minutes, $seconds);
		} else {
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_MINUTES', $minutes, $seconds);
		}
	}

	/**
	 * word-sensitive substring function with html tags awareness
	 *
	 * @param string text The text to cut
	 * @param int len The maximum length of the cut string
	 * @param array Array of tags to exclude
	 *
	 * @return string The modified html content
	 * @deprecated use CjLibUtils::substrws
	 */
	public static function substrws( $text, $len=180, $tags=array()) {

		if(function_exists('mb_strlen')){
			
			if( (mb_strlen($text, 'UTF-8') > $len) ) {
			
				$whitespaceposition = mb_strpos($text, ' ', $len, 'UTF-8')-1;
			
				if( $whitespaceposition > 0 ) {
			
					$chars = count_chars(mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8'), 1);
			
					if (!empty($chars[ord('<')]) && $chars[ord('<')] > $chars[ord('>')]){
							
						$whitespaceposition = mb_strpos($text, '>', $whitespaceposition, 'UTF-8') - 1;
					}
			
					$text = mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8');
				}
			
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
			
					if( !empty($aBuffer[1]) ) {
			
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
			
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
			
							foreach( $aBuffer[1] as $index => $tag ) {
			
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag){
			
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		} else {
			
			if( (strlen($text) > $len) ) {
			
				$whitespaceposition = strpos($text, ' ', $len)-1;
			
				if( $whitespaceposition > 0 ) {
			
					$chars = count_chars(substr($text, 0, $whitespaceposition + 1), 1);
			
					if ($chars[ord('<')] > $chars[ord('>')]){

						$whitespaceposition = strpos($text, '>', $whitespaceposition) - 1;
					}
			
					$text = substr($text, 0, $whitespaceposition + 1);
				}
			
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
			
					if( !empty($aBuffer[1]) ) {
			
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
			
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
			
							foreach( $aBuffer[1] as $index => $tag ) {
			
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag){
			
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		}
		
		return preg_replace('#<p[^>]*>(\s|&nbsp;?)*</p>#', '', $text);;
	}

	/**
	 * Gets the ip address of the user from request
	 *
	 * @return string ip address
	 * @deprecated 17/09/2018
	 */
	public static function get_user_ip_address() {

		return CjLibUtils::getUserIpAddress();
	}
	
	/**
	 * Gets the array containing location details matching the ip address.
	 * 
	 * @param string $ip IP address
	 * 
	 * @return array An array containing city, country and country code (country_code) information
	 */
	public static function get_user_location($ip)
	{
		return CjLibUtils::getUserLocation($ip);
	}
	
	/**
	 * Gets the clean content from the request variable named <code>var</code>. If the second parameter passed as false, html tags will be stripped out.
	 *
	 * @param string $var
	 * @param boolean $html
	 * @return Ambigous <string, mixed>
	 * @deprecated 2018-09-17
	 */
	public static function get_clean_var($var, $html = true, $default = ''){
		
		$value = $html ? JRequest::getVar($var, $default, 'post', 'string', JREQUEST_ALLOWRAW) : JRequest::getVar($var, $default, 'post', 'string');
		$value = empty($_POST[$var]) ? $default : $_POST[$var];
		
		$filter = new CleanXSS();
		return $filter->sanitize($value);
	}

	/**
	 * A wrapper function for the Joomla mail API to send emails.
	 *
	 * @param string $from from email address
	 * @param string $fromname name of the sender
	 * @param string $recipient reciepient email
	 * @param string $subject email subject
	 * @param string $body body of the email
	 * @param boolean $mode true if html mode enabled, false otherwise
	 * @param string $cc email addresses in cc
	 * @param string $bcc email addresses in bcc
	 * @param string $attachment attachment
	 * @param string $replyto replyto email address
	 * @param string $replytoname reply to name
	 *
	 * @return mixed True if successful, a JError object otherwise
	 */
	public static function send_email($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null){

		// Get a JMail instance
		$mail = JFactory::getMailer();

		$mail->setSender(array($from, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($body);

		// Are we sending the email as HTML?
		if ($mode) {
				
			$mail->IsHTML(true);
		}

		if(!is_array($recipient)){
		
			$recipient = explode(',', $recipient);
		}

		$mail->addRecipient($recipient);
		$mail->addCC($cc);
		$mail->addBCC($bcc);
		$mail->addAttachment($attachment);

		// Take care of reply email addresses
		if (is_array($replyto)) {
				
			$numReplyTo = count($replyto);
				
			for ($i=0; $i < $numReplyTo; $i++){

				$mail->addReplyTo(array($replyto[$i], $replytoname[$i]));
			}
		} elseif (isset($replyto)) {
				
			$mail->addReplyTo(array($replyto, $replytoname));
		}

		return  $mail->Send();
	}

	/**
	 * Loads the modules assigned to position <code>$position</code>
	 *
	 * @param string $position
	 * @return string The output of the script
	 */
	public static function load_module_position($position) {

		jimport( 'joomla.application.module.helper' );
		
		if(JModuleHelper::getModules($position)) {
				
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('modules');
			$options	= array('style' => 'xhtml');
				
			return $renderer->render($position, $options, null);
		}else {
				
			return '';
		}
	}

	/**
	 * Generate a random character string
	 *
	 * @param int $length length of the string to be generated
	 * @param string $chars characters to be considered, default alphanumeric characters.
	 *
	 * @return string randomly generated string
	 * @deprecated use CjLibUtils::getRandomKey 2018-09-17
	 */
	public static function generate_random_key($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){

		return CjLibUtils::getRandomKey($length, $chars);
	}

	/**
	 * Throws new exception with message and error code for j1.6 or above, calls <code>JError::raiseError</code> otherwise.
	 *
	 * @param string $msg message
	 * @param int $code error code
	 * 
	 * @throws Exception
	 */
	public static function throw_error($msg, $code){

		if(APP_VERSION == '1.5'){
				
			JError::raiseError( $code, $msg);
		}else{
				
			throw new Exception($msg, $code);
		}
	}

	/**
	 * Convert special characters to HTML entities with UTF-8 encoding.
	 * 
	 * @param string $var content to be escaped
	 */
	public static function escape($var){

		return htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Triggers a Joomla event
	 * 
	 * @param string $group The plugin type, relates to the sub-directory in the plugins directory
	 * @param string $event The event to trigger
	 * @param array $params An array of arguments
	 * 
	 * @deprecated 2017-09-17
	 */
	public static function trigger_event($group, $event, $params=null){

		JPluginHelper::importPlugin( $group );

		$app = JFactory::getApplication();
		$app->triggerEvent($event, $params);
	}
	
	/**
	 * Returns menu id of active menu. If the <code>itemid</code> is set, Itemid request variable name is prepended for use in JRoute urls.
	 * 
	 * @param boolean $itemid Returns Itemid appended text if set to true else int id of active menu
	 * @param string $url The url from which the menu id to be retrieved instead of active menu item
	 * @param boolean $cat_request If true, catid from request is prepended to the itemid text. Not applicable if <code>itemid</code> set to false. 
	 * 
	 * @return Ambigous <string, number, mixed> active menu id if <code>itemid</code> not set, else return Itemid request variable or empty if not found.
	 */
	public static function get_active_menu_id($itemid = true, $url = null, $cat_request = false)
	{
	    $app = JFactory::getApplication();
		$menu = $app->getMenu('site');
		$active = $menu->getActive();
		$menuid = 0;
		$catparam = '';
		
		if(empty($url))
		{
			if(!empty($active->id))
			{
				$menuid = $active->id;
			}
		
			if($menuid <= 0)
			{
			    $menuid = $app->input->getInt('Itemid', 0);
			}
		} 
		else 
		{
			if(!empty($active->id))
			{
				$menuitems = $menu->getItems('link', $url, false);
				if(!empty($menuitems))
				{
					foreach ($menuitems as $menuitem)
					{
						if(!empty($menuitem->id) && $menuitem->id == $active->id)
						{
							$menuid = $menuitem->id;
							break;
						}
					}
					
					if($menuid == 0 && !empty($menuitems[0]->id))
					{
						$menuid = $menuitems[0]->id;
					}
				}
			} 
			else 
			{
				$menuitem = $menu->getItems('link', $url, true);
				if(!empty($menuitem))
				{
					$menuid = $menuitem->id;
				}
			}
		}
		
		if( $itemid && $cat_request )
		{
		    $catid = $app->input->getInt('catid', 0);
			$catparam = $catid > 0 ? '&catid='.$catid : '';
		}
		
		return $menuid > 0 ? ( $itemid ? $catparam.'&Itemid='.$menuid : $menuid ) : '';
	}
	
	/**
	 * Loads the comments from the installed/selected comment system. The comment system <code>type</code> should tell which comment system need to be used to load the comments from. The possible values are:<br><br>
	 * jcomment - JComments (id and title are required) <br>
	 * fbcomment - Facebook comment system (url is required)<br>
	 * disqus - Disqus comment system (id, title, identifier and url are required)<br>
	 * intensedebate - Intense Debate comment system (id, title, identifier and url are required)<br>
	 * jacomment - JAComment system (id and title are required)<br>
	 * jomcomment - JomComment (id is required)<br><br>
	 * Passing any other value will silently skips the code. In all the above cases, <code>type</code> and <code>app_name</code> are required parameters. 
	 * While <code>type</code> specifies the comment system to be used, <code>app_name</code> is the Joomla extension name (ex: com_appname) which is loading the comments for its content.
	 *  
	 * @param string $type comment system type
	 * @param string $app_name extension name
	 * @param int $id id of the content for which the comments are being loaded
	 * @param string $title title of the content
	 * @param string $url page url in case of facebook/disqus/intensedebate comment system.
	 * @param string $identifier disqus username in case of disqus/intensedebate comment system.
	 * @param object $item the item object for kommento
	 * 
	 * @return string the code to render the comments.
	 */
	public static function load_comments($type, $app_name, $id=0, $title='', $url = '', $identifier='', $item = null)
	{
	    $app = JFactory::getApplication();
		switch ($type){
			
			case 'jcomment':
				$path = JPATH_ROOT.'/components/com_jcomments/jcomments.php';
				
				if (file_exists($path)) 
				{
					require_once($path);
					return JComments::showComments($id, $app_name, $title);
				}
				break;

			case 'fbcomment':
				return '
					<div id="fb-root"></div>
					<script type="text/javascript">
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0]; 
							if (d.getElementById(id)) return; 
							js = d.createElement(s); 
							js.id = id;
							js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
							fjs.parentNode.insertBefore(js, fjs); 
						}(document, "script", "facebook-jssdk"));
					</script>
					<div class="fb-comments" data-href="'.$url.'" data-num-posts="5" data-width="640"></div>';

			case 'disqus':
				return '
					<div id="disqus_thread"></div>
					<script type="text/javascript">
						var disqus_shortname = "'.$identifier.'";
// 						var disqus_developer = 1;
						var disqus_identifier = "'.$id.'";
						var disqus_url = "'.$url.'";
						var disqus_title = "'.$title.'";
						(function() {
							var dsq = document.createElement("script"); 
							dsq.type = "text/javascript"; 
							dsq.async = true;
							dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
							(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>';

			case 'intensedebate':
				return '
					<script>
						var idcomments_acct = "'.$identifier.'";
						var idcomments_post_id = "'.$id.'";
						var idcomments_post_url = "'.$url.'";
					</script>
					<span id="IDCommentsPostTitle" style="display:none"></span>
					<script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>';

			case 'jacomment':
				if(!$app->input->getInt('print') && file_exists(JPATH_SITE.'/components/com_jacomment/jacomment.php') && file_exists(JPATH_SITE.'/plugins/system/jacomment.php')){
					
					$_jacCode = "#{jacomment(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";
					$_jacCodeDisableid = "#{jacomment(\s)off.*}#i";
					$_jacCodeDisable = "#{jacomment(\s)off}#i";
					
					if(!preg_match($_jacCode, $title) && !preg_match($_jacCodeDisable, $title) && !preg_match($_jacCodeDisableid, $title)) 
					{
						return '{jacomment contentid='.$id.' option='.$app_name.' contenttitle='.$title.'}';
					}
				}
				break;

			case 'jomcomment':
				$path = JPATH_PLUGINS.'/content/jom_comment_bot.php';
				
				if(file_exists($path)) 
				{
					include_once( $path );
					return jomcomment($id, $app_name);
				}
				break;
				
			case 'kommento':
				$api = JPATH_ROOT.'/components/com_komento/bootstrap.php';
				if(file_exists($api))
				{
					require_once $api;
					$item->text = $item->introtext = !empty($item->description) ? $item->description : '';
					if(class_exists('KT')) {
						return KT::commentify( $app_name, $item );
					} else {
						return Komento::commentify( $app_name, $item );
					}
				}
				break;
				
			case 'ccomment':
				$utils = JPATH_ROOT . '/components/com_comment/helpers/utils.php';
				if(file_exists($utils))
				{
					JLoader::discover('ccommentHelper', JPATH_ROOT . '/components/com_comment/helpers');
					return ccommentHelperUtils::commentInit($app_name, $item);
				}
				break;
		}
	}
	
	public static function get_comment_count($type, $app_name, $id=0, $title='', $url = '', $identifier='', $item = null)
	{
		switch ($type)
		{
			case 'jcomment':
				$path = JPATH_ROOT.'/components/com_jcomments/jcomments.php';
				if (file_exists($path)) 
				{
					require_once($path);
					return JComments::getCommentsCount($id, $app_name);
				}
				break;
		
			case 'fbcomment':
				$json = json_decode(file_get_contents('https://graph.facebook.com/?ids=' . $url));
				return isset($json->$url->comments) ? $json->$url->comments : 0;
		
			case 'disqus':
// 				$document = JFactory::getDocument();
// 				$document->addScript('http://' + $identifier + '.disqus.com/count.js');
				break;
		
			case 'intensedebate':
				break;
					
			case 'jacomment':
				break;
		
			case 'kommento':
				break;
		
			case 'ccomment':
				break;
		}
	}
	
	/**
	 * Gets the version check update from corejoomla servers. Returns associative array with values of <ul>
	 * <li>version - version on servers</li>
	 * <li>released - release date of the version on server</li>
	 * <li>changelog - for future use</li>
	 * <li>status - boolean true if the version is equal, false otherwise</li>
	 * <li>connect - true if shondalai.com is connected with the function, false otherwise.</li></ul>
	 * 
	 * @param string $component component to check for update
	 * @param version $current_version current version of the component to compare
	 * 
	 * return array update status of the component 
	 */
	public static function get_component_update_check($component, $current_version)
	{
		$url = 'https://shondalai.com/wp-content/uploads/autoupdates/extensions.xml';
		$data = '';
		$check = array();
		$check['connect'] = 0;
		$check['current_version'] = $current_version;
		
		//try to connect via cURL
		if(function_exists('curl_init') && function_exists('curl_exec')) 
		{
			$ch = @curl_init();
		
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, 0);
			
			//http code is greater than or equal to 300 ->fail
			@curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			//timeout of 5s just in case
			@curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$data = @curl_exec($ch);
			
			@curl_close($ch);
		}
		
		if( !empty($data) && strstr($data, '<corejoomla>') ) 
		{
			$xml = new SimpleXMLElement($data);
			foreach($xml->extension as $extension)
			{
				if($extension['name'] == $component && $extension['jversion'] == '1.7')
				{
					$check['version']		= $extension->version;
					$check['released']		= $extension->released;
					$check['changelog']		= $extension->changelog;
					$check['status']		= version_compare( $check['current_version'], $check['version'] );
					$check['connect']		= 1;
					
					break;
				}
			}
		} 
		else 
		{
			$check['version']		= 'N/A';
			$check['released']		= 'N/A';
			$check['changelog']		= 'N/A';
			$check['status']		= '0';
			$check['connect']		= 0;
		}
		
		return $check;		
	}
	
	/**
	 * Function to get the login redirect url based on Joomla version.
	 * 
	 * @param string $redirect_url redirect url used after login
	 * @param string $itemid itemid
	 */
	public static function get_login_url($redirect_url, $itemid){
		
		return APP_VERSION == '1.5' 
				? JRoute::_("index.php?option=com_user&view=login".$itemid."&return=".$redirect_url) 
				: JRoute::_("index.php?option=com_users&view=login".$itemid."&return=".$redirect_url);
	}
	
	/**
	 * Function to get browser information. 
	 * Courtesy: 
	 * 	http://www.php.net/manual/en/function.get-browser.php#101125
	 * 	http://www.geekpedia.com/code47_Detect-operating-system-from-user-agent-string.html
	 */
	public static function get_browser($u_agent = null)
	{
		$u_agent = !empty($u_agent) ? $u_agent : $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		
		$OSList = array(
				'Windows 3.11' => '(Win16)',
				'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
				'Windows 98' => '(Windows 98)|(Win98)',
				'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
				'Windows 2000 Service Pack 1' => '(Windows NT 5.01)',
				'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
				'Windows Server 2003' => '(Windows NT 5.2)',
				'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
				'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
				'Windows 8' => '(Windows NT 6.2)|(Windows 8)',
				'Windows 8.1' => '(Windows NT 6.3)|(Windows 8)',
				'Windows 10' => '(Windows NT 10)|(Windows 10)',
				'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
				'Windows ME' => '(Windows ME)|(Windows 98; Win 9x 4.90 )',
				'Windows CE' => '(Windows CE)',
				'Mac OS X Kodiak (beta)' => '(Mac OS X beta)',
				'Mac OS X Cheetah' => '(Mac OS X 10.0)',
				'Mac OS X Puma' => '(Mac OS X 10.1)',
				'Mac OS X Jaguar' => '(Mac OS X 10.2)',
				'Mac OS X Panther' => '(Mac OS X 10.3)',
				'Mac OS X Tiger' => '(Mac OS X 10.4)',
				'Mac OS X Leopard' => '(Mac OS X 10.5)',
				'Mac OS X Snow Leopard' => '(Mac OS X 10.6)',
				'Mac OS X Lion' => '(Mac OS X 10.7)',
				'Mac OS X Mountain Lion' => '(Mac OS X 10.8)',
				'Mac OS X Mavericks' => '(Mac OS X 10.9)',
				'Mac OS X Yosemite' => '(Mac OS X 10.10)',
				'Mac OS X' => '(Mac OS X)',
				'Mac OS' => '(Mac_PowerPC)|(PowerPC)|(Macintosh)',
				'Open BSD' => '(OpenBSD)',
				'SunOS' => '(SunOS)',
				'Solaris 11' => '(Solaris\/11)|(Solaris11)',
				'Solaris 10' => '((Solaris\/10)|(Solaris10))',
				'Solaris 9' => '((Solaris\/9)|(Solaris9))',
				'CentOS' => '(CentOS)',
				'QNX' => '(QNX)',
				'UNIX' => '(UNIX)',
				'Ubuntu 14.04 LTS' => '(Ubuntu\/14.04)|(Ubuntu 14.04)',
				'Ubuntu 12.10' => '(Ubuntu\/12.10)|(Ubuntu 12.10)',
				'Ubuntu 12.04 LTS' => '(Ubuntu\/12.04)|(Ubuntu 12.04)',
				'Ubuntu 11.10' => '(Ubuntu\/11.10)|(Ubuntu 11.10)',
				'Ubuntu 11.04' => '(Ubuntu\/11.04)|(Ubuntu 11.04)',
				'Ubuntu 10.10' => '(Ubuntu\/10.10)|(Ubuntu 10.10)',
				'Ubuntu 10.04 LTS' => '(Ubuntu\/10.04)|(Ubuntu 10.04)',
				'Ubuntu 9.10' => '(Ubuntu\/9.10)|(Ubuntu 9.10)',
				'Ubuntu 9.04' => '(Ubuntu\/9.04)|(Ubuntu 9.04)',
				'Ubuntu 8.10' => '(Ubuntu\/8.10)|(Ubuntu 8.10)',
				'Ubuntu 8.04 LTS' => '(Ubuntu\/8.04)|(Ubuntu 8.04)',
				'Ubuntu 6.06 LTS' => '(Ubuntu\/6.06)|(Ubuntu 6.06)',
				'Red Hat Linux' => '(Red Hat)',
				'Red Hat Enterprise Linux' => '(Red Hat Enterprise)',
				'Fedora 17' => '(Fedora\/17)|(Fedora 17)',
				'Fedora 16' => '(Fedora\/16)|(Fedora 16)',
				'Fedora 15' => '(Fedora\/15)|(Fedora 15)',
				'Fedora 14' => '(Fedora\/14)|(Fedora 14)',
				'Chromium OS' => '(ChromiumOS)',
				'Google Chrome OS' => '(ChromeOS)',
				'Linux' => '(Linux)|(X11)',
				'OpenBSD' => '(OpenBSD)',
				'FreeBSD' => '(FreeBSD)',
				'NetBSD' => '(NetBSD)',
				'Andriod' => '(Android)',
				'iPod' => '(iPod)',
				'iPhone' => '(iPhone)',
				'iPad' => '(iPad)',
				'OS/8' => '(OS\/8)|(OS8)',
				'Older DEC OS' => '(DEC)|(RSTS)|(RSTS\/E)',
				'WPS-8' => '(WPS-8)|(WPS8)',
				'BeOS' => '(BeOS)|(BeOS r5)',
				'BeIA' => '(BeIA)',
				'OS/2 2.0' => '(OS\/220)|(OS\/2 2.0)',
				'OS/2' => '(OS\/2)|(OS2)',
				'BlackBerry' => '(blackberry)',
				'Search engine or robot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves\/Teoma)|(ia_archiver)'
		);
		
		foreach($OSList as $os=>$match)
		{
			// Find a match
			if (preg_match('/'.$match.'/i', $u_agent))
			{
				$platform = $os;
				break;
			}
		}

		// Next get the name of the useragent yes seperately and for good reason
		$ub = '';
		if(preg_match('/MSIE|trident/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} 
		elseif(preg_match('/Edge/i',$u_agent))
		{
			$bname = 'Microsoft Edge';
			$ub = "Edge";
		} 
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} 
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} 
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		} 
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		 
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			
			// we have no matching number just continue
		}
		 
		// see how many we have
		$i = count($matches['browser']);
		
		if ($i != 1) 
		{
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
			{
				$version = !empty($matches['version'][0]) ? $matches['version'][0] : "";
			} 
			else 
			{
				$version = !empty($matches['version'][1]) ? $matches['version'][1] : "";
			}
		} 
		else 
		{
			$version = !empty($matches['version'][0]) ? $matches['version'][0] : "";
		}
		 
		// check if we have a number
		if ($version == null || $version == "") 
		{
			$version = "Unknown";
		}
		 
		return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
		);
	}
	
	/**
	 * Downloads the geolite database file from maxmind database source location.
	 * 
	 * GeoLite data created by MaxMind, available from http://www.maxmind.com
	 */
	public static function download_geoip_databases($force = false)
	{
	    $path = JPATH_ROOT.'/media/com_cjlib/geoip/';
	    if($force && file_exists($path.'GeoLite2-City.mmdb'))
	    {
	        JFile::delete($path.'GeoLite2-City.mmdb');
	    }
	    
	    if(!file_exists($path)) 
	    {
	        JFolder::create($path);
	    }
	    
		if(file_exists($path.'GeoLite2-City.mmdb'))
		{
			$filemtime = filemtime($path.'GeoLite2-City.mmdb');
			if((time() - $filemtime) >= 30*86400)
			{
				JFile::delete($path.'GeoLite2-City.mmdb');
			} 
			else 
			{
				return false;
			}
		}
		
		$params = JComponentHelper::getParams('com_cjlib');
		$licenseKey = $params->get('maxmind_license_key');
		if(empty($licenseKey))
		{
		    return false;
		}
		
		// Download the package at the URL given.
		$packageUrl = 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&suffix=tar.gz&license_key='.$licenseKey;
		$fileName = JInstallerHelper::downloadPackage($packageUrl);

		// Was the package downloaded?
		if (!$fileName)
		{
		    throw new Exception('Unable to download GeoIP database from MaxMind.', 500);
		}
		
		$config   = JFactory::getConfig();
		$tmpDir = $config->get('tmp_path');
		CJFunctions::unpackMaxMindDb($tmpDir.'/', $fileName, $path, 'GeoLite2-City.mmdb');
		
		return true;
	}
	
	/**
	 * Uncompresses a gzipped file to destination.
	 * 
	 * @param string $source source file path
	 * @param string $target destination file path
	 */
	public static function uncompress($source, $target) 
	{
		$sfp = gzopen($source, "rb");
		$fp = fopen($target, "w");
	
		while ($string = gzread($sfp, 4096)) 
		{
			fwrite($fp, $string, strlen($string));
		}
		
		gzclose($sfp);
		fclose($fp);
	}
	
	public static function unpackMaxMindDb($srcDir, $srcFile, $destDir, $destFile)
	{
	    $p_filename = $srcDir . $srcFile;
	    
	    // Path to the archive
	    $archivename = $p_filename;
	    
	    // Clean the paths to use for archive extraction
	    $tarFileName = JFile::stripExt($srcFile);
	    $extractname = \JPath::clean(dirname($p_filename) . '/' . $tarFileName);
	    $archivename = \JPath::clean($archivename);
	    
	    // Temporary folder to extract the archive into
	    $tmpdir = uniqid('install_');
	    $extractdir = \JPath::clean(dirname($p_filename) . '/' . $tmpdir .'/');
	    
	    // Do the unpacking of the archive
	    try
	    {
	        $sfp = gzopen($archivename, "rb");
	        $fp = fopen($extractname, "w");
	        
	        while ($string = gzread($sfp, 4096))
	        {
	            fwrite($fp, $string, strlen($string));
	        }
	        
	        gzclose($sfp);
	        fclose($fp);
	        
	        $archive = new Archive(array('tmp_path' => JFactory::getConfig()->get('tmp_path')));
	        $extract = $archive->extract($extractname, $extractdir);
	    }
	    catch (\Exception $e)
	    {
	        return false;
	    }
	    
	    if (!$extract)
	    {
	        return false;
	    }
	    
	    $sourceFileName = \JPath::clean($extractdir.JFile::stripExt($tarFileName).'/'.$destFile);
	    $destFileName = \JPath::clean($destDir . $destFile);
	    JFile::move($sourceFileName, $destFileName);
	    
	    JFolder::delete($extractdir);
	    JFile::delete($extractname);
	    
	    return true;
	}
	
	/**
	 * Downloads the file from remote server to specified location on local server. Uses regular file operations or cURL or sockets (whichever available first) to download the file.
	 * 
	 * @param url $source_url url of the source file
	 * @param string $target_folder target folder name, should end with / 
	 * @param string $target_file target file name.
	 */
	public static function download_file($source_url, $target_folder, $target_file)
	{
		if(file_exists($target_file))
		{
			JFile::delete($target_file);
		} 
		else 
		{
			JFolder::create($target_folder);
		}
		
		//try to connect via fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) 
		{
			//set socket timeout
			ini_set('default_socket_timeout', 5);
			$handle = fopen ($source_url, 'rb');
			
			if($handle)
			{
				$download = fopen($target_folder.$target_file, "wb");
				if($download)
				{
					while(!feof($handle)) 
					{
						fwrite($download, fread($handle, 1024 * 8 ), 1024 * 8 );
					}
				}
			}
			
			if($handle)
			{
				fclose($handle);
			}
		}
		//try to connect via cURL
		else if(function_exists('curl_init') && function_exists('curl_exec')) 
		{
			$fh = fopen ($target_folder.$target_file, "w");
			$options = array(
					CURLOPT_FILE => $fh,
					CURLOPT_URL => $source_url,
					CURLOPT_TIMEOUT => 28800,
					CURLOPT_FAILONERROR => 1,
					CURLOPT_HEADER => 0,
					CURLOPT_TIMEOUT => 5
			);
			
			$ch = curl_init();
			curl_setopt_array($ch, $options);
			curl_exec($ch);
			curl_close($ch);
			fclose($fh);
		}
		//try to connect via fsockopen
		else if(function_exists('fsockopen') && $data == '') 
		{
			$errno = 0;
			$errstr = '';
			
			$parts = StringHelper::parse_url($source_url);
			$hostname = $parts['host'];
			unset($parts['scheme']);
			unset($parts['host']);
			$filename = CJFunctions::join_url($parts);
			
			//timeout handling: 5s for the socket and 5s for the stream = 10s
			$fsock = fsockopen($hostname, 80, $errno, $errstr, 5);
			
			if ($fsock) {
			
				fputs($fsock, 'GET '.$filename.' HTTP/1.1\r\n');
				fputs($fsock, 'HOST: '.$hostname.'\r\n');
				fputs($fsock, 'Connection: close\r\n\r\n');
			
				//force stream timeout...
				stream_set_blocking($fsock, 1);
				stream_set_timeout($fsock, 5);
			
				$get_info = false;
				$download = fopen($target_folder.$target_file, 'wb');
			
				while (!feof($fsock)) {
						
					if ($get_info) {
			
						fwrite($download, fread($handle, 1024 * 8 ), 1024 * 8 );
					} else {
			
						if (fgets($fsock, 8192) == '\r\n') {
								
							$get_info = true;
						}
					}
				}

				fclose($fsock);
			}
		}
	}
	
	private static function join_url($parts, $encode=true) {
		
		if ( $encode ){
			
			if ( isset( $parts['user'] ) ){
				
				$parts['user'] = rawurlencode( $parts['user'] );
			}
			
			if ( isset( $parts['pass'] ) ){
				
				$parts['pass'] = rawurlencode( $parts['pass'] );
			}
			
			if ( isset( $parts['host'] ) && !preg_match( '!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'] ) ){
				
				$parts['host'] = rawurlencode( $parts['host'] );
			}
			
			if ( !empty( $parts['path'] ) ){
				
				$parts['path'] = preg_replace( '!%2F!ui', '/', rawurlencode( $parts['path'] ) );
			}
			
			if ( isset( $parts['query'] ) ){
				
				$parts['query'] = rawurlencode( $parts['query'] );
			}
			
			if ( isset( $parts['fragment'] ) ){
				
				$parts['fragment'] = rawurlencode( $parts['fragment'] );
			}
		}

		$url = '';
		
		if ( !empty( $parts['scheme'] ) ){
			
			$url .= $parts['scheme'] . ':';
		}
		
		if ( isset( $parts['host'] ) ){
			
			$url .= '//';
			
			if ( isset( $parts['user'] ) ){
				
				$url .= $parts['user'];
				
				if ( isset( $parts['pass'] ) ){ 
					
					$url .= ':' . $parts['pass'];
				}
				
				$url .= '@';
			}
			
			if ( preg_match( '!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'] ) ){
				
				$url .= '[' . $parts['host'] . ']'; // IPv6
			} else {
				
				$url .= $parts['host'];             // IPv4 or name
			}
			
			if ( isset( $parts['port'] ) ){
				
				$url .= ':' . $parts['port'];
			}
			
			if ( !empty( $parts['path'] ) && $parts['path'][0] != '/' ){
				
				$url .= '/';
			}
		}
		
		if ( !empty( $parts['path'] ) ){
			
			$url .= $parts['path'];
		}
		
		if ( isset( $parts['query'] ) ){
			
			$url .= '?' . $parts['query'];
		}
		
		if ( isset( $parts['fragment'] ) ){
			
			$url .= '#' . $parts['fragment'];
		}
		
		return $url;
	}
	
	public static function send_messages_from_queue($records = 60, $delay = 0, $simulated = true, $force_ids = array()){
		
		if($simulated){
		    $params = JComponentHelper::getParams('com_cjlib');
			
		    if($params->get('enable_manual_cron')){
				require_once CJLIB_PATH.'/framework/virtualcron.php';
				
				$delay = $delay > 0 ? $delay : intval($params->get('max_delay_per_batch', 10));
				$vcron = new virtualcron($delay, CJLIB_PATH.'/framework/virtualcron.txt');
				
				if (!$vcron->allowAction()){
					return false;
				}
			} else {
				return false;
			}
		}

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$from = $app->get('mailfrom' );
		$fromname = $app->get('fromname' );
		$message_ids = array();
		$sent = array();
		$ids = array();
		
		$query = $db->getQuery(true);
		$query->select('id')->from('#__corejoomla_messagequeue');
		
		if(!empty($force_ids)){
			
			$query->where('id in ('.implode(',', $force_ids).') and status = 0');
		} else {
			
			$query->where('status = 0')->where('retries < 4');
		}
		
		$db->setQuery($query, 0, $records);
		$message_ids = array();
		
		try {
		
			$message_ids = $db->loadColumn();
		} catch (Exception $e){
			
			return false;
		}
		
		if(empty($message_ids))
		{
			return $sent;
		}
		
		$messages = CjMailHelper::getMessage($message_ids);
		if(empty($messages))
		{
			return $sent;
		}
		
		foreach ($messages as $item)
		{
			try{
			    $query = $db->getQuery(true)
    			    ->update($db->qn('#__corejoomla_messagequeue'))
    			    ->set($db->qn('retries') . ' = ' . $db->qn('retries') . ' + 1')
    			    ->where($db->qn('id') . ' = ' . (int) $item->id);
			    $db->setQuery($query);
			    $db->execute();
			    
				$return = CJFunctions::send_email(
						$from, $fromname, $item->to_addr, $item->subject, $item->description, $item->html, $item->cc_addr, $item->bcc_addr, $item->attachment);
			
				if($return === true){
						
					$sent[] = $item->id;
				}
				
				$ids[] = $item->id;
			}catch (Exception $e){
			
				// Add logger
				$date = JFactory::getDate()->format('Y.m.d');
				JLog::addLogger(array('text_file' => 'com_cjlib.'.$date.'.log.php'), JLog::ALL, 'com_cjlib');
				JLog::add('Send Messages From Queue - Error: '.print_r($e, true), JLog::ERROR, 'com_cjlib');
			}
		}
		
		if(!empty($ids)){
		
			$created = JFactory::getDate()->toSql();
			$query = $db->getQuery(true);
		
			$query
			->update($db->qn('#__corejoomla_messagequeue'))
			->set($db->qn('status').' = 1, processed = '.$db->q($created))
			->where('id in ('.implode(',', $ids).')');
		
			$db->setQuery($query);
		
			try{
		
				$db->execute();
			} catch (Exception $e){
					
				return false;
			}
		}
		
		return $sent;
	}
	
	public static function get_supported_avatars(){
		
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'cjblog' => JText::_('COM_CJLIB_EXTENSION_CJBLOG'),
				'gravatar' => JText::_('COM_CJLIB_EXTENSION_GRAVATAR'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'cb' => JText::_('COM_CJLIB_EXTENSION_COMMUNITY_BUILDER'),
				'kunena' => JText::_('COM_CJLIB_EXTENSION_KUNENA'),
				'aup' => JText::_('COM_CJLIB_EXTENSION_ALPHA_USERPOINTS'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
			);
	}
	
	public static function get_supported_streams(){
	
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
		);
	}
	
	public static function get_supported_point_systems(){
	
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'cjblog' => JText::_('COM_CJLIB_EXTENSION_CJBLOG'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'aup' => JText::_('COM_CJLIB_EXTENSION_ALPHA_USERPOINTS'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
		);
	}
	
	public static function quoteName($name, $db){
		
		return APP_VERSION == '1.5' ? $db->nameQuote($name) : $db->quoteName($name);
	}
	
	public static function get_joomla_categories_table_markup($categories, $options = array()){
	
		$max_columns = isset($options['max_columns']) ? $options['max_columns'] : 3;
		$max_children = isset($options['max_children']) ? $options['max_children'] : 0;
		$base_url = isset($options['base_url']) ? $options['base_url'] : '';
		$menu_id = isset($options['menu_id']) ? $options['menu_id'] : '';
		$attribs = isset($options['link_attribs']) ? $options['link_attribs'] : array();
		$stat_primary = isset($options['stat_primary']) ? $options['stat_primary'] : null;
		$stat_secondary = isset($options['stat_secondary']) ? $options['stat_secondary'] : null;
		$stat_tooltip = isset($options['stat_tooltip']) ? $options['stat_tooltip'] : null;
	
		$num_rows = ceil(count($categories) / $max_columns);
		$table = '<div class="row-fluid">';
		$colspan = 'span'.(12/$max_columns);
		$itemid = 0;
	
		foreach($categories as $category){
				
			if($itemid % $num_rows == 0) $table = $table.'<div class="'.$colspan.'">';
				
			$url = JRoute::_($base_url.'&id='.$category->id.':'.$category->alias.$menu_id);
			$title = CJFunctions::escape($category->title);
				
			if(!empty($stat_primary) && !empty($stat_secondary)){
	
				$title = $title.' <small><span class="muted text-muted">('.$category->$stat_primary.'/'.$category->$stat_secondary.')</span></small>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category->title, $category->$stat_primary, $category->$stat_secondary) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			} else if(!empty($stat_primary)){
	
				$title = $title.' <small><span class="muted text-muted">('.$category->$stat_primary.')</span></small>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category->title, $category->$stat_primary) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			}
				
			$table = $table.'<ul class="unstyled list-unstyled no-space-left">';
			$table = $table.'<li class="parent-item">'.JHtml::link($url, $title, $attribs).'</li>';
				
			if($max_children > 0 && count($category->children) > 0){
	
				$child_count = 0;
	
				foreach($category->children as $child){
						
					$url = JRoute::_($base_url.'&id='.$child['id'].':'.$child['alias'].$menu_id);
					$title = CJFunctions::escape($child['title']);
						
					if(!empty($stat_primary) && !empty($stat_secondary)){
	
						$title = $title.' ('.$child[$stat_primary].' / '.$child[$stat_secondary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary, $stat_secondary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					} else if(!empty($stat_primary)){
	
						$title = $title.' ('.$child[$stat_primary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					}
						
					$table = $table.'<li class="child-item">'.JHtml::link($url, $title, $attribs).'</li>';
						
					if($child_count + 1 == $max_children) break;
						
					$child_count++;
				}
			}
				
			$table = $table.'</ul>';
				
			if(($itemid % $num_rows == $num_rows - 1) || ($itemid + 1 == count($categories))) $table = $table.'</div>';
				
			$itemid++;
		}
	
		$table = $table.'</div>';
	
		return $table;
	}
	
	/**
	 * Checks if the countries table has the language specific country names added. If yes, returnes the language code otherwise returns *
	 * @return string language code if the coutry codes exist for the current user language else *
	 */
	public static function get_country_language(){
		
		$db = JFactory::getDbo();
		$language = JFactory::getLanguage();
		
		$query = $db->getQuery(true);
		$query
			->select('count(*)')
			->from('#__corejoomla_countries')
			->where('language = '.$db->quote($language->getTag()))
			->where('published = 1');
		
		$db->setQuery($query);
		$count = $db->loadResult();
		
		return $count > 0 ? $language->getTag() : '*';
	}
	
	/**
	 * Gets the list of countries filtered by the user language if available, otherwise gets countries of language *
	 * 
	 * @return Array List of countries 
	 */
	public static function get_country_names(){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$language = CJFunctions::get_country_language();
		
		$query
			->select('country_code, country_name')
			->from('#__corejoomla_countries')
			->where('language = '.$db->q($language))
			->where('published = 1')
			->order('country_name');
		
		$db->setQuery($query);
		$countries = $db->loadObjectList('country_code');
		
		return !empty($countries) ? $countries : array();
	}
	
	/**
	 * Gets the first image location in the html data provided, if the data contains img tags.
	 * 
	 * @param string $html the data where the search is performed
	 * @return string src attribute of the first img tag if found
	 */
	public static function get_first_image($html)
	{
		preg_match_all('/<img .*src=["|\']([^"|\']+)/i', $html, $matches);
		foreach ($matches[1] as $key=>$value) 
		{
			return $value;
		}
	
		return '';
	}
	
	/**
	 * 
	 * @param unknown $seed
	 * @return unknown
	 * 
	 * @deprecated 2018-09-17
	 */
	public static function get_hash($seed){
		
		// replace this with JApplicationHelper::getHash when Joomla 2.5 support no more exist
		return md5(JFactory::getConfig()->get('secret') . $seed);
	}
}