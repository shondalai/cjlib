<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CJLib {
	
	static $_bootstrap_loaded = false;
	
	/**
	 * Imports required package to be used in applications
	 * 
	 * @param string $library ex: corejoomla.tree.nestedtree
	 */
	public static function import($package, $cjprefixed = false, $force = false, $custom_tag = false){
		
		$lib = '';
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		
		switch ($package){
				
			case 'corejoomla.framework.core':
				
				$lib = CJLIB_PATH.'/framework/functions.php';
				break;
				
			case 'corejoomla.template.core':
				
				$lib = CJLIB_PATH.'/template/template.php';
				break;
				
			case 'corejoomla.nestedtree.core':
				
				$lib = CJLIB_PATH.'/lib/tree/nestedtree.php';
				break;
				
			case 'corejoomla.nestedtree.ui':

				$document->addStyleSheet(CJLIB_URI.'/tree/ui/css/jquery.mcdropdown.min.css');
				$document->addScript(CJLIB_URI.'/tree/ui/scripts/jquery.bgiframe.js');
				$document->addScript(CJLIB_URI.'/tree/ui/scripts/jquery.mcdropdown.min.js');
				break;
				
			case 'corejoomla.forms.form':
			
				$lib = CJLIB_PATH.'/forms/form.php';
				break;
			
			case 'corejoomla.forms.image':
			
				$lib = CJLIB_PATH.'/forms/image.php';
				break;
				
			case 'corejoomla.ui.bootstrap':
				
				$bsloaded = (!empty($app->cjbootstrap) || isset($app->cjbsjs)) ? true : false;
				
				if(!$bsloaded || $force){
					
					if(APP_VERSION < 3){
						
						$document->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
						
						if($cjprefixed){
							
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.rtl.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.rtl.min.css', $custom_tag);
							} else {
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.min.css', $custom_tag);
							}
						} else {
							
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.min.css', $custom_tag);
							}
							
							CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap-responsive.min.css', $custom_tag);
						}

						CJFunctions::load_jquery(array('libs'=>array()));
						$document->addScript(CJLIB_URI.'/bootstrap/js/bootstrap.min.js');
					} else {
	
						JHtml::_('bootstrap.framework');
						JHtmlBootstrap::loadCss(true, $document->direction);
					}
					
					$app->cjbootstrap = true;
					$app->cjbsjs = true;
				}
				
				break;
				
			case 'corejoomla.ui.fa.bootstrap':
				
				$bsloaded = (!empty($app->cjbootstrap) || isset($app->cjbsjs)) ? true : false;
				
				if(!$bsloaded || $force){

					if(APP_VERSION < 3){
				
						$document->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
				
						if($cjprefixed){
								
							if(JFactory::getLanguage()->isRTL()){

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.fontawesome.rtl.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.fontawesome.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.min.css', $custom_tag);
							}
						} else {
								
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.fontawesome.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.fontawesome.min.css', $custom_tag);
							}
							
							CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap-responsive.min.css', $custom_tag);
						}

						$document->addCustomTag('<!--[if IE 7]><link rel="stylesheet" href="'.CJLIB_URI.'/bootstrap/css/font-awesome-ie7.min.css"><![endif]-->');
				
						CJFunctions::load_jquery(array('libs'=>array()));
						$document->addScript(CJLIB_URI.'/bootstrap/js/bootstrap.min.js');
					} else {
				
						JHtml::_('bootstrap.framework');
						JHtmlBootstrap::loadCss(true, $document->direction);
						CJFunctions::load_jquery(array('libs'=>array('fontawesome'), 'custom_tag'=>$custom_tag));
					}

					$app->cjbootstrap = true;
					$app->cjbsjs = true;
					$app->jqueryplugins = !empty($app->jqueryplugins) ? $app->jqueryplugins : array();
					$app->jqueryplugins[] = 'fontawesome';
				} else if ($bsloaded) {
					
					if(APP_VERSION >= 3){
						
						CJFunctions::load_jquery(array('libs'=>array('fontawesome'), 'custom_tag'=>$custom_tag));
					}
				}
				
				break;
				
			default:
				
				$lib = CJLIB_PATH.'/dummy.php';
		}
		
		if(\Joomla\CMS\Filesystem\File::exists($lib)) {
			
			require_once $lib;
		}
	}
	
	public static function behavior($behavior, $options = array()){
		
		require_once CJLIB_PATH.'/framework/behavior.php';
		$custom_tag = isset($options['customtag']) ? $options['customtag'] : false;
		
		switch ($behavior){
			
			case 'jquery':
				CjLibBehavior::jquery($custom_tag);
				break;
			
			case 'bootstrap':
				$load_bsjs = isset($options['loadjs']) ? $options['loadjs'] : true;
				$load_bscss = isset($options['loadcss']) ? $options['loadcss'] : true;
				$local_css = isset($options['localcss']) ? $options['localcss'] : false;
				
				CjLibBehavior::bootstrap($load_bsjs, $load_bscss, $local_css, $custom_tag);
				break;
				
			case 'bscore':
				CjLibBehavior::bscore($custom_tag);
				break;
				
			case 'fontawesome':
				CjLibBehavior::fontawesome($custom_tag);
				break;
		}
	}
}