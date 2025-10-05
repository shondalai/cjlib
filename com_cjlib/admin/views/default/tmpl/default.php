<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2018 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Extension;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

defined('_JEXEC') or die();

$params = ComponentHelper::getParams('com_cjlib');
$link = Route::_('index.php?option=com_cjlib&task=process&secret=YOURSECRETKEY', false, -1);
$link = str_replace('/administrator/','/', $link);
$rowClass = APP_VERSION < 4 ? 'row-fluid' : 'row';
$span = !empty( $this->sidebar) ? 'col-md-10' : 'col-md-12';
?>
<div id="cj-wrapper" class="<?php echo $rowClass;?>">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2 col-md-2">
    	<?php echo $this->sidebar; ?>
    </div>
    <?php endif;?>
    <div class="<?php echo $span;?>">
    	<div id="j-main-container">
    		<div class="<?php echo $rowClass;?>">
    			<div class="span8 col-md-8">
    				<div class="panel panel-default card">
    					<div class="panel-heading card-header">
    						<div class="panel-title card-title"><?php echo Text::_('COM_CJLIB_ACTIVATE_YOUR_LICENSE');?></div>
    					</div>
    					<div class="panel-body card-body">
    						<form name="adminForm" id="adminForm" action="<?php echo Route::_('index.php?option=com_cjlib&task=save_config');?>" method="post">
    							<div class="<?php echo $rowClass;?> mb-4">
    								<div class="span3 col-md-3">
    									<?php echo Text::_('COM_CJLIB_LICENSE_EMAIL');?>
    								</div>
    								<div class="span9 col-md-9">
    									<input type="email" name="license_email" class="form-control" placeholder="<?php echo Text::_('COM_CJLIB_LICENSE_EMAIL');?>" value="<?php echo $params->get('license_email');?>">
    								</div>
    							</div>
    							<div class="<?php echo $rowClass;?> mb-4">
    								<div class="span3 col-md-3">
    									<?php echo Text::_('COM_CJLIB_LICENSE_KEY');?>
    								</div>
    								<div class="span9 col-md-9">
    									<input type="text" name="license_key" class="form-control" placeholder="<?php echo Text::_('COM_CJLIB_LICENSE_KEY');?>" value="<?php echo $params->get('license_key');?>">
    								</div>
    							</div>
    							
    							<?php if(!empty($params->get('license_key'))):?>
    							<div class="alert alert-info"><i class="fa fa-check-circle"></i> <?php echo Text::_('COM_CJLIB_LICENSE_ACTIVATED');?></div>
    							<button type="submit" class="btn btn-danger">
    								<?php echo Text::_('COM_CJLIB_DEACTIVATE_LICENSE');?>
    							</button>
    							<?php else:?>
    							<button type="submit" class="btn btn-primary"><?php echo Text::_('COM_CJLIB_ACTIVATE_LICENSE');?></button>
    							<?php endif;?>
    							
    							<h4 class="mt-3"><?php echo Text::_('COM_CJLIB_FORGOT_LICENSE');?></h4>
    							<p>
    								<?php echo Text::_('COM_CJLIB_GET_YOUR_LICENSE_KEY');?>
    								<a href="https://shondalai.com/my-account/lost-license" target="_blank">https://shondalai.com/my-account/lost-license</a>
    							</p>
    							
                        		<input type="hidden" name="option" value="com_cjlib" />
                        		<input type="hidden" name="task" value="<?php echo !empty($params->get('license_key')) ? 'license.deactivate' : 'license.activate';?>" />
                        		<input type="hidden" name="view" value="default" />
                        		<?php echo HTMLHelper::_( 'form.token' ); ?>
                        	</form>
    					</div>
    				</div>
    			</div>
    			<div class="span4 col-md-4">
    				<div class="panel panel-default card mb-3">
    					<div class="panel-heading card-header">
    						<div class="panel-title card-title"><?php echo Text::_('COM_CJLIB');?></div>
    					</div>
    					<div class="panel-body card-body">
    						<?php
    						$component = ComponentHelper::getComponent('com_cjlib');
                            $db = Factory::getDbo();
                            $extension = new Extension( $db );
    						$extension->load($component->id);
    						$manifest = new Registry($extension->manifest_cache);
    						
    						echo 'v'.$manifest->get('version');
    						?>
    					</div>
    				</div>
    				<div class="panel panel-default card">
    					<div class="panel-heading card-header">
    						<div class="panel-title card-title"><?php echo Text::_('COM_CJLIB_CRON_URL');?></div>
    					</div>
    					<div class="panel-body card-body">
    						<?php echo $link;?>
    					</div>
    				</div>
    			</div>
    		</div>
		</div>
	</div>
</div>