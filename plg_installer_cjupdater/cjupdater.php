<?php
/**
 * @package     corejoomla.site
 * @subpackage  plg_cjupdater
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die();

class PlgInstallerCjupdater extends CMSPlugin {

    private $baseUrl = 'shondalai.com';

    /**
     *
     * @var String your extension identifier, to retrieve its params
     */
    private $extension = 'com_cjlib';

    /**
     * Handle adding credentials to package download request
     *
     * @param string $url
     *            from which package is going to be downloaded
     * @param array $headers
     *            to be sent along the download request (key => value format)
     *            
     * @return boolean if credentials have been added to request or not our business, false otherwise (credentials not set by user)
     */
    public function onInstallerBeforePackageDownload (&$url, &$headers)
    {
        // are we trying to update our extension?
        if (strpos($url, $this->baseUrl) === false)
        {
            if(strpos( $url, 'www.corejoomla.com' ) !== false ) {
                // Legacy redirect
                JLoader::import('joomla.application.component.helper');
                $params = ComponentHelper::getParams( $this->extension );
                $downloadId = $params->get('update_credentials_download_id', '');
                
                // bind credentials to request by appending it to the download url
                if (! empty($downloadId) && !stripos($url, '.zip'))
                {
                    $separator = strpos($url, '?') !== false ? '&' : '?';
                    $url .= $separator . 'dlid=' . $downloadId;
                }
            }
            
            return true;
        }
        
        $params = ComponentHelper::getParams( $this->extension );
        $license_key = $params->get('license_key');
        $license_email = $params->get('license_email');

        if( !empty( $license_key ) && !empty( $license_email ) && !stripos($url, '.zip') ) {
            // Build the arguments
            $args = array(
                'email'       => $license_email,
                'key'         => $license_key,
                'instance' 	  => Uri::getInstance()->root(),
                'product'     => 'all-access-pack'
            );
            
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url = $url . $separator . http_build_query( $args );
        }

        return true;
    }
}
