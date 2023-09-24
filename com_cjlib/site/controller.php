<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibController extends Joomla\CMS\MVC\Controller\BaseController {
	
    function __construct() {

	    $this->input->set('view', 'default');
        parent::__construct();
    }
}
