<?php

use Joomla\CMS\Log\Log;

Log::add( sprintf( 'Using the virtualcron.php library through files located in %1$s is deprecated, load the files from %2$s instead.',
	__DIR__, JPATH_ROOT . '/components/com_cjlib/lib/misc/' ), Log::WARNING, 'deprecated' );

require_once JPATH_ROOT . '/components/com_cjlib/lib/misc/virtualcron.php';
