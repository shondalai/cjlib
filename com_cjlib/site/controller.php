<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

class CjLibController extends JControllerLegacy {
	
    function __construct() {
    	
    	JRequest::setVar('view', 'default');
        parent::__construct();
    }
}
?>