<?php
/**
 * @package     corejoomla.site
 * @subpackage  plg_corejoomla_socials
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class PlgCorejoomlaSocials extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		
		// Load plugin language
		$this->loadLanguage();
		
		// Load CjLib Api Framework
		require_once JPATH_ROOT.'/components/com_cjlib/framework.php';
		CJLib::import('corejoomla.framework.core');
	}
	
	public function onSocialsDisplay($context, $params)
	{
		$myparams	= $this->params;
		$size 		= $myparams->get('jssocials_size', 12);
		$theme		= $myparams->get('jssocials_theme', 'flat');
		$shares     = $myparams->get('allowed_services', array("email", "twitter", "facebook", "linkedin", "pinterest"));
		
		return CjHtml::_('jssocials', array('size'=>$size, 'theme'=>$theme, 'shares'=>$shares, 'custom' => true));
	}
}