<?php
if (class_exists ( 'JLog' )) {
	JLog::add ( sprintf ( 'Using the class.upload.php library through files located in %1$s is deprecated, load the files from %2$s instead.', 
			__DIR__, JPATH_ROOT . '/components/com_cjlib/lib/misc/' ), JLog::WARNING, 'deprecated' );
}

require_once JPATH_ROOT.'/components/com_cjlib/lib/misc/class.upload.php';
?>