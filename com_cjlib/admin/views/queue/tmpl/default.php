<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2018 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

$span = !empty( $this->sidebar) ? 'col-md-10' : 'col-md-12';
?>
<div class="row" id="cj-wrapper">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="col-md-2">
    	<?php echo $this->sidebar; ?>
    </div>
    <?php endif;?>
    <div class="<?php echo $span;?>">
    	<div id="j-main-container">
        	<form action="<?php echo JRoute::_('index.php?option=com_cjlib&view=queue');?>" method="post" name="adminForm" id="adminForm">
        		<table class="adminlist table table-bordered table-striped">
        			<thead><?php echo $this->loadTemplate('head');?></thead>
        			<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        			<tbody><?php echo $this->loadTemplate('body');?></tbody>
        		</table>
        		<div style="display: none;">
        			<input type="hidden" name="task" value="queue" />
        			<input type="hidden" name="boxchecked" value="0" />
        			<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
        			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
        			<input type="hidden" name="cjlib_page_id" id="cjlib_page_id" value="queue">
        			<?php echo JHtml::_('form.token'); ?>
        		</div>
        	</form>
        </div>
	</div>
</div>