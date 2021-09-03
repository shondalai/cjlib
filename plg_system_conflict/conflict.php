<?php
/**
 * @package     corejoomla.site
 * @subpackage  plg_system_conflict
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class PlgSystemConflict extends JPlugin
{

	public function onBeforeCompileHead()
    {
    	if (JFactory::getApplication()->isClient('administrator'))
    	{
    		return true;
    	}
    	
    	// Set the variables.
    	$input = JFactory::getApplication()->input;
    	$extension = $input->get('option', '', 'cmd');
    	
    	// Check if the highlighter is enabled.
    	if ($extension != 'com_communityquiz')
    	{
    		return true;
    	}
    	
    	// Check if the highlighter should be activated in this environment.
    	$document = JFactory::getDocument();
    	if ($document->getType() !== 'html' || $input->get('tmpl', '', 'cmd') === 'component')
    	{
    		return true;
    	}
        
    	$document->addStyleDeclaration(
    			'#rt-header {position: relative;} .jf_head_set {display: none !important;}' .
    			'@media (max-width: 960px) {#jf_mm_menu {height: 0; width: 0;} .jf_head_set {display: none !important;}}' . 
    			'@media (max-width: 568px) {#jf_mm_menu {height: 0; width: 0;} .jf_head_set {display: none !important;}}');
    	
        return true;
    }
}
