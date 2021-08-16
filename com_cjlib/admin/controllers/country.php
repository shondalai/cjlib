<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibControllerCountry extends JControllerForm
{
	public function __construct ($config = array())
	{
		parent::__construct($config);
	}

	public function save($key = NULL, $urlVar = NULL)
	{
		$user = JFactory::getUser();
		if(!$user->authorise('core.edit', 'com_cjlib')) 
		{
			echo json_encode(array('error'=>JText::_('COM_CJLIB_MSG_NOT_AUTHORIZED')));
		}
		else 
		{
			$app = JFactory::getApplication();
			$model = $this->getModel('countries');
			
			$id = $app->input->getInt('id', 0);
			$name = $app->input->getString('country_name', '');
			
			if($id && !empty($name) && $model->save_country_name($id, $name))
			{
				echo json_encode(array('data'=>1));
			}
			else
			{
				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			}
		}
		
		jexit();
	}
}
