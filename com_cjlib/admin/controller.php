<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

class CjLibController extends JControllerLegacy 
{
	protected $default_view = 'default';
	
    public function display ($cachable = false, $urlparams = false)
    {
    	
    	$custom_tag = true;
    	$document = JFactory::getDocument();
    	
    	if(APP_VERSION < 4) {
    	    CjLib::behavior('bootstrap', array('loadcss' => false));
    	    CJLib::behavior('bscore', array('customtag'=>$custom_tag));
    	    CjScript::_('fontawesome', array('custom'=>$custom_tag));
    	} else {
    	    $wa = $document->getWebAssetManager();
    	    $wa->useStyle('fontawesome');
    	}
    	
    	CJFunctions::add_script(JUri::base(true).'/components/com_cjlib/assets/js/cj.lib.min.js', $custom_tag);
    	CJFunctions::add_css_to_document($document, JUri::root(true).'/media/com_cjlib/framework/cj.framework.css', $custom_tag);
    	CJFunctions::add_css_to_document($document, JUri::base(true).'/components/com_cjlib/assets/css/styles.css', $custom_tag);
    	
    	JToolBarHelper::preferences('com_cjlib');
    	
    	parent::display();
    	return $this;
    }
}
?>