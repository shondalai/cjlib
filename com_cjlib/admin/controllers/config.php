<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibControllerConfig extends JControllerForm
{
	public function __construct ($config = array())
	{
		parent::__construct($config);
	}

	public function save($key = NULL, $urlVar = NULL)
	{
		$model = $this->getModel('config');
		if(!$model->save())
		{
			$this->setRedirect('index.php?option=com_cjlib', JText::_('MSG_ERROR_PROCESSING'));
		}
		else
		{
			$this->setRedirect('index.php?option=com_cjlib', JText::_('MSG_CONFIG_SAVED'));
		}
	}
	
	public function updategeoip() 
	{
	    if(!CJFunctions::download_geoip_databases(true)) 
	    {
	        $this->setRedirect('index.php?option=com_cjlib', JText::_('COM_CJLIB_NOTICE_EMPTY_MAXMIND_LICENSE_KEY'));
	    }
	    else 
	    {
	        $this->setRedirect('index.php?option=com_cjlib', JText::_('MSG_CONFIG_SAVED'));
	    }
	}
}
