<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibController extends Joomla\CMS\MVC\Controller\AdminController
{
	protected $default_view = 'default';
	
    public function display ($cachable = false, $urlparams = false)
    {
    	
    	$custom_tag = true;
    	$document = \Joomla\CMS\Factory::getDocument();
    	
    	if(APP_VERSION < 4) {
    	    CjLib::behavior('bootstrap', array('loadcss' => false));
    	    CJLib::behavior('bscore', array('customtag'=>$custom_tag));
    	    CjScript::_('fontawesome', array('custom'=>$custom_tag));
    	} else {
    	    $wa = $document->getWebAssetManager();
    	    $wa->useStyle('fontawesome');
    	}
    	
    	CJFunctions::add_script(\Joomla\CMS\Uri\Uri::base(true).'/components/com_cjlib/assets/js/cj.lib.min.js', $custom_tag);
    	CJFunctions::add_css_to_document($document, \Joomla\CMS\Uri\Uri::root(true).'/media/com_cjlib/framework/cj.framework.css', $custom_tag);
    	CJFunctions::add_css_to_document($document, \Joomla\CMS\Uri\Uri::base(true).'/components/com_cjlib/assets/css/styles.css', $custom_tag);
    	
    	\Joomla\CMS\Toolbar\ToolbarHelper::preferences('com_cjlib');
    	
    	parent::display();
    	return $this;
    }
}
?>