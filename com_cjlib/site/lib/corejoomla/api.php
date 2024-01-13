<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die;

require_once 'utils.php';
require_once 'dateutils.php';

use CB\Plugin\Activity\Table\ActivityTable;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

class CjLibApi {

	public function getUserAvatar( $profileApp, $avatarApp, $userId, $name, $height = 48, $email = null, $attribs = [], $imgAttribs = [] ) {
		$profileUrl  = $this->getUserProfileUrl( $profileApp, $userId, true );
		$avatarImage = $this->getUserAvatarImage( $avatarApp, $userId, $email, $height, false, $name, $imgAttribs );

		if ( $profileUrl == '#' )
		{
			$attribs['onclick'] = 'return false;';
		}

		return HTMLHelper::link( $profileUrl, $avatarImage, $attribs );
	}

	public function getUserAvatarImage( $app, $userId, $email = null, $height = 48, $urlOnly = true, $alt = '', $imgAttribs = [] ) {
		$avatar              = '';
		$userId              = ! empty( $userId ) ? $userId : 0;
		$imgAttribs['class'] = isset( $imgAttribs['class'] ) ? $imgAttribs['class'] : 'img-avatar';

		switch ( $app )
		{
			case 'sociable':
				$api = JPATH_ROOT . '/components/com_sociable/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$profileApi = SociableApi::getProfileApi();
					$avatar     = $profileApi->getUserAvatarImage( $userId, $height );

					if ( $urlOnly == false )
					{
						$imgAttribs['height'] = $height . 'px';
						$imgAttribs['style']  = 'max-height: ' . $height . 'px; max-width: ' . $height . 'px;';
						$avatar               = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs )
						                        . ' />';
					}
				}

				break;

			case 'cjforum':
				$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$profileApi = CjForumApi::getProfileApi();
					$avatar     = $profileApi->getUserAvatarImage( $userId, $height );

					if ( $urlOnly == false )
					{
						$imgAttribs['height'] = $height . 'px';
						$imgAttribs['style']  = 'max-height: ' . $height . 'px; max-width: ' . $height . 'px;';
						$avatar               = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs )
						                        . ' />';
					}
				}

				break;

			case 'cjblog':
				$api = JPATH_ROOT . '/components/com_cjblog/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$profileApi = CjBlogApi::getProfileApi();
					$avatar     = $profileApi->getUserAvatarImage( $userId, $height );

					if ( $urlOnly == false )
					{
						$imgAttribs['height'] = $height . 'px';
						$imgAttribs['style']  = 'max-height: ' . $height . 'px; max-width: ' . $height . 'px;';
						$avatar               = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs )
						                        . ' />';
					}
				}

				break;

			case 'easyprofile':
				$api = JPATH_ROOT . '/components/com_jsn/helpers/helper.php';
				if ( file_exists( $api ) )
				{
					$user   = JsnHelper::getUser( $userId );
					$avatar = $user->getValue( 'avatar' );
					if ( $urlOnly == false )
					{
						$imgAttribs['height'] = $height . 'px';
						$imgAttribs['style']  = 'max-height: ' . $height . 'px; display: block; margin: 0 auto';
						$avatar               = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs )
						                        . ' />';
					}
				}

				break;

			case 'jomsocial':
				require_once JPATH_ROOT . '/components/com_community/defines.community.php';
				require_once JPATH_ROOT . '/components/com_community/libraries/core.php';
				require_once JPATH_ROOT . '/components/com_community/helpers/string.php';

				$user   = CFactory::getUser( $userId );
				$avatar = $user->getThumbAvatar();

				if ( $urlOnly == false )
				{
					$imgAttribs['height'] = $height . 'px';
					$avatar               = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs )
					                        . ' />';
				}

				break;

			case 'cb':
				global $_CB_framework, $_CB_database, $ueConfig, $mainframe;

				$api = JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';

				if ( ! is_file( $api ) )
				{
					return;
				}
				require_once( $api );

				cbimport( 'cb.database' );
				cbimport( 'cb.tables' );
				cbimport( 'cb.field' );
				cbimport( 'language.front' );

				outputCbTemplate( $_CB_framework->getUi() );

				//TODO: Here
				$imgAttribs['height'] = $height . 'px';

				if ( $userId > 0 )
				{
					$cbUser = CBuser::getInstance( $userId );

					if ( $cbUser !== null )
					{
						$avatar = $cbUser->getField( 'avatar', null, 'php', 'profile', 'list' );
						$alt    = $cbUser->getField( 'name' );
						$avatar = $avatar['avatar'];
					}
				}
				else
				{
					if ( $height <= 90 )
					{
						$avatar = selectTemplate() . 'images/avatar/tnnophoto_n.png';
					}
					else
					{
						$avatar = selectTemplate() . 'images/avatar/nophoto_n.png';
					}
				}

				if ( $urlOnly == false )
				{
					$avatar = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs ) . ' />';
				}

				break;

			case 'gravatar':
				if ( null == $email && $userId > 0 )
				{
					try
					{
						$db    = Factory::getDbo();
						$query = $db->getQuery( true )->select( 'email' )->from( '#__users' )->where( 'id = ' . $userId );
						$db->setQuery( $query );

						$email = $db->loadResult();
					}
					catch ( Exception $e )
					{
					}
				}

				$avatar = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?s=' . $height . '&d=mm&r=g';

				if ( $urlOnly == false )
				{
					$avatar = HTMLHelper::image( $avatar, $alt, $imgAttribs );
				}

				break;

			case 'kunena':
				if ( class_exists( 'KunenaForum' ) && KunenaForum::isCompatible( '4.0' ) && KunenaForum::installed() )
				{
					KunenaForum::setup();
					$user = KunenaFactory::getUser( $userId );

					if ( $urlOnly )
					{
						$avatar = $user->getAvatarURL( $height, $height );
					}
					else
					{
						$avatarHtml = $user->getAvatarImage( 'avatar', $height, $height );
						$userLink   = $user->getLink( null, $avatarHtml );
					}
				}
				break;

			case 'aup':
				$api_AUP = JPATH_SITE . '/components/com_alphauserpoints/helper.php';

				if ( file_exists( $api_AUP ) )
				{
					require_once( $api_AUP );

					if ( $urlOnly )
					{
						$avatar = AlphaUserPointsHelper::getAvatarPath( $userId );
					}
					else
					{
						$avatar = AlphaUserPointsHelper::getAupAvatar( $userId, 0, $height, $height );
					}
				}

				break;

			case 'atup':

				$api_AUP = JPATH_SITE . '/components/com_altauserpoints/helper.php';

				if ( file_exists( $api_AUP ) )
				{
					require_once $api_AUP;
					if ( $urlOnly )
					{
						$avatar = AltaUserPointsHelper::getAvatarPath( $userId );
					}
					else
					{
						$avatar = AltaUserPointsHelper::getAupAvatar( $userId, 0, $height, $height );
					}
				}
				break;

			case 'easysocial':
				$api = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

				if ( file_exists( $api ) )
				{
					require_once $api;
					$my           = Foundry::user( $userId );
					$avatar       = $my->getAvatar( $height < 64 ? SOCIAL_AVATAR_SMALL : ( $height < 128 ? SOCIAL_AVATAR_MEDIUM : SOCIAL_AVATAR_LARGE ) );
					$imgAttribs[] = [ 'style' => 'height: ' . $height . 'px' ];

					if ( $urlOnly == false )
					{
						$avatar = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs ) . ' />';
					}
				}

				break;

			case 'slogin':
				if ( PluginHelper::isEnabled( 'slogin_integration', 'profile' ) )
				{
					include_once JPATH_ROOT . '/plugins/slogin_integration/profile/helper.php';
					$profile = plgProfileHelper::getProfile( $userId );
					$avatar  = Uri::root() . '/' . $profile->avatar;

					if ( $urlOnly == false )
					{
						$avatar = '<img src="' . $avatar . '" alt="' . $alt . '" ' . ( is_array( $imgAttribs ) ? ArrayHelper::toString( $imgAttribs ) : $imgAttribs ) . ' />';
					}
				}
				break;
		}

		return $avatar;
	}

	public function getUserProfileUrl( $system, $userId, $urlOnly = true, $name = 'Guest', $attribs = [] ) {
		$url    = '#';
		$userId = $userId ? $userId : Factory::getUser()->id;

		if ( $userId )
		{
			switch ( $system )
			{
				case 'sociable':

					$api = JPATH_ROOT . '/components/com_sociable/lib/api.php';

					if ( file_exists( $api ) )
					{
						require_once $api;
						$profileApi = SociableApi::getProfileApi();
						$url        = $profileApi->getUserProfileLink( $userId, 'name', true );
					}
					break;

				case 'cjforum':

					$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';

					if ( file_exists( $api ) )
					{
						require_once $api;
						$profileApi = CjForumApi::getProfileApi();
						$url        = $profileApi->getUserProfileLink( $userId, 'name', true );
					}
					break;

				case 'cjblog':

					$api = JPATH_ROOT . '/components/com_cjblog/lib/api.php';

					if ( file_exists( $api ) )
					{
						require_once $api;
						$profileApi = CjBlogApi::getProfileApi();
						$url        = $profileApi->getUserProfileLink( $userId, 'name', true );
					}
					break;

				case 'easyprofile':

					$api = JPATH_ROOT . '/components/com_jsn/helpers/helper.php';

					if ( file_exists( $api ) )
					{
						require_once $api;
						$user = JsnHelper::getUser( $userId );
						$url  = $user->getLink();
					}

					break;

				case 'jomsocial':

					$jspath = JPATH_ROOT . '/components/com_community/libraries/core.php';

					if ( file_exists( $jspath ) )
					{
						include_once( $jspath );
						$url = CRoute::_( 'index.php? option=com_community&view=profile&userid=' . $userId );
					}

					break;

				case 'cb':

					global $_CB_framework, $_CB_database, $ueConfig, $mainframe;

					$api = JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';

					if ( ! is_file( $api ) )
					{
						return;
					}
					require_once( $api );

					cbimport( 'cb.database' );
					cbimport( 'cb.tables' );
					cbimport( 'language.front' );
					cbimport( 'cb.field' );

					$url = cbSef( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . ( (int) $userId ) . getCBprofileItemid( true, false ) );

					break;

				case 'kunena':

					if ( $this->_initialize_kunena() && $userId > 0 )
					{
						$user = KunenaFactory::getUser( $userId );
						if ( $user === false )
						{
							break;
						}
						$url = KunenaRoute::_( 'index.php?option=com_kunena&view=user&userid=' . $user->userid, true );
					}

					break;

				case 'aup':

					$api_AUP = JPATH_SITE . '/components/com_alphauserpoints/helper.php';

					if ( file_exists( $api_AUP ) )
					{
						require_once( $api_AUP );
						$url = AlphaUserPointsHelper::getAupLinkToProfil( $userId );
					}

					break;

				case 'atup':

					$api_AUP = JPATH_SITE . '/components/com_altauserpoints/helper.php';

					if ( file_exists( $api_AUP ) )
					{
						require_once( $api_AUP );
						$url = AltaUserPointsHelper::getAupLinkToProfil( $userId );
					}

					break;

				case 'easysocial':

					$api = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

					if ( file_exists( $api ) )
					{

						require_once $api;
						$my   = Foundry::user( $userId );
						$url  = FRoute::profile( [ 'id' => $my->getAlias() ] );
						$name = $my->getName();
					}

					break;
			}
		}

		if ( $url && ! $urlOnly )
		{
			$url = HTMLHelper::link( $url, $name, $attribs );
		}

		return ( null == $url ) ? $name : $url;
	}

	/**
	 * Streams activity to the selected app.
	 *
	 * Required parameters of activity:
	 * <ul>
	 *  <li>$activity->type -> activity type, e.g. com_cjforum.newtopic</li>
	 *  <li>$activity->title -> title of the activity</li>
	 *  <li>$activity->description -> description of the activity</li>
	 *  <li>$activity->userId -> optional, user who's activity is being added.</li>
	 *  <li>$activity->featured -> optional, is this featured?</li>
	 *  <li>$activity->language -> optional, language</li>
	 *  <li>$activity->itemId -> optional, activity attached to an item</li>
	 *  <li>$activity->parentId -> optional, parent id of this item_id</li>
	 *  <li>$activity->href -> url of the activity target</li>
	 *  <li>$activity->length -> max length of the description to be shown.</li>
	 * </ul>
	 *
	 * @param   unknown  $app
	 * @param   unknown  $activity
	 */
	public function pushActivity( $app, $activity ) {
		switch ( $app )
		{
			case 'cjforum':
				$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';
				if ( file_exists( $api ) )
				{
					// required parameters in the stream input:
					// $activity->type -> activity type, e.g. com_cjforum.newtopic
					// $activity->title -> title of the activity
					// $activity->description -> description of the activity
					// $activity->userId -> optional, user who's activity is being added.
					// $activity->featured -> optional, is this featured?
					// $activity->language -> optional, language
					// $activity->itemId -> optional, activity attached to an item
					// $activity->parentId -> optional, parent id of this item_id
					// $activity->length -> optional, description length

					require_once $api;
					$streamApi = CjForumApi::getStreamApi();
					$streamApi->push( $activity );
				}
				break;

			case 'jomsocial':
				$api = JPATH_ROOT . '/components/com_community/libraries/core.php';

				if ( file_exists( $api ) && ! empty( $activity->title ) && ! empty( $activity->type ) )
				{
					include_once $api;
					CFactory::load( 'libraries', 'activities' );

					$act               = new stdClass();
					$act->cmd          = 'wall.write';
					$act->target       = 0;
					$act->app          = 'wall';
					$act->cid          = 0;
					$act->comment_id   = CActivities::COMMENT_SELF;
					$act->like_id      = CActivities::LIKE_SELF;
					$act->actor        = $activity->userId;
					$act->title        = $activity->title;
					$act->comment_type = $activity->type;
					$act->like_type    = $activity->type;
					$act->access       = 0;

					if ( ! empty( $activity->description ) && ! empty( $activity->length ) )
					{
						$content = CjLibUtils::substrws( $activity->description, $activity->length );

						if ( ! empty( $activity->href ) )
						{
							$act->content = $content . '
								<div style="margin-top: 5px;">
									<div style="float: right; font-weight: bold; font-size: 12px;">
										<a href="' . $activity->href . '">' . Text::_( 'COM_CJLIB_READ_MORE' ) . '</a>
									</div>
									<div style="clear: both;"></div>
								</div>';
						}
						else
						{

							$act->content = $content;
						}
					}

					CActivityStream::add( $act );
				}
				break;

			case 'easysocial':
				$api = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
				if ( file_exists( $api ) )
				{
					require_once $api;

					$stream   = Foundry::stream();
					$template = $stream->getTemplate();
					$content  = $activity->length > 0 ? CjLibUtils::substrws( $activity->description, $activity->length ) : $activity->description;

					$template->setActor( $activity->userId, 'user' );
					$template->setContext( $activity->itemId, $activity->type );
					$template->setTitle( $activity->title );
					$template->setContent( html_entity_decode( strip_tags( $content ) ) );
					$template->setVerb( 'create' );
					// $template->setSideWide( true );
					$template->setType( 'full' );

					$stream->add( $template );
				}
				break;

			case 'cb':

				$cbTable = new ActivityTable();
				$cbTable->set( 'type', $activity->type );
				$cbTable->set( 'title', $activity->title );
				$cbTable->set( 'message', $content );
				$cbTable->set( 'user_id', $activity->userId );

				$cbTable->store();
				break;
		}
	}

	public function deleteActivity( $app, $activity ) {
		switch ( $app )
		{
			case 'cjforum':
				break;

			case 'jomsocial':
				break;

			case 'easysocial':
				$api = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					Foundry::stream()->delete( $activity->id, $activity->type );
				}
				break;

			case 'cb':
				break;
		}
	}

	public function awardPoints( $app, $userId, $options ) {
		$points      = ! empty( $options['points'] ) ? $options['points'] : 0;
		$reference   = ! empty( $options['reference'] ) ? $options['reference'] : null;
		$title       = ! empty( $options['title'] ) ? $options['title'] : null;
		$description = ! empty( $options['info'] ) ? $options['info'] : null;

		switch ( $app )
		{
			case 'rewardify':
				Factory::getApplication()->bootComponent( 'com_rewardify' )->getUserPoints()->assign(
					[
						'rule'        => $options['function'],
						'userid'      => $userId,
						'ref'         => $reference,
						'points'      => $points,
						'title'       => $title,
						'description' => $description,
					]
				);
				break;

			case 'sociable':

				$api = JPATH_ROOT . '/components/com_sociable/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$pointsApi = SociableApi::getPointsApi();
					$pointsApi->awardPoints( $options['function'], $userId, $points, $reference, $title, $description );
				}
				break;

			case 'cjforum':

				$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$pointsApi = CjForumApi::getPointsApi();
					$pointsApi->awardPoints( $options['function'], $userId, $points, $reference, $title, $description );
				}
				break;

			case 'cjblog':

				if ( file_exists( JPATH_ROOT . '/components/com_cjblog/lib/api.php' ) )
				{
					include_once JPATH_ROOT . '/components/com_cjblog/lib/api.php';
					$pointsApi = CjBlogApi::getPointsApi();
					$pointsApi->awardPoints( $options['function'], $userId, $points, $reference, $title, $description );
				}
				elseif ( file_exists( JPATH_ROOT . '/components/com_cjblog/api.php' ) ) // legacy compatibility
				{
					include_once JPATH_ROOT . '/components/com_cjblog/api.php';
					CjBlogApi::award_points( $options['function'], $userId, $points, $reference, $description );
				}

				break;

			case 'jomsocial':

				$api = JPATH_ROOT . '/components/com_community/libraries/core.php';
				if ( file_exists( $api ) && ! empty( $options['function'] ) )
				{
					include_once $api;
					include_once JPATH_SITE . '/components/com_community/libraries/userpoints.php';
					CuserPoints::assignPoint( $options['function'], $userId );
				}
				break;

			case 'aup':

				$api = JPATH_SITE . '/components/com_alphauserpoints/helper.php';

				if ( file_exists( $api ) && ! empty( $options['function'] ) )
				{
					require_once $api;
					$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $userId );
					AlphaUserPointsHelper::newpoints( $options['function'], $aupid, $reference, $description, $points );
				}
				break;

			case 'atup':

				$api = JPATH_SITE . '/components/com_altauserpoints/helper.php';

				if ( file_exists( $api ) && ! empty( $options['function'] ) )
				{
					require_once $api;
					$aupid = AltaUserPointsHelper::getAnyUserReferreID( $userId );
					AltaUserPointsHelper::newpoints( $options['function'], $aupid, $reference, $description, $points );
				}
				break;

			case 'easysocial':

				$api = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
				if ( file_exists( $api ) )
				{
					require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/easysocial.php';

					if ( $points )
					{
						$lib  = ES::points();
						$user = ES::user( $userId );
						$lib->assignCustom( $user->id, $points, $title );
					}
					else
					{
						require_once $api;
						Foundry::points()->assign( $options['function'], $options['component'], $userId );
					}
				}

				break;
		}
	}

	public function getProfileIdFromRequest( $profileApp, $default = 0 ) {
		$app    = Factory::getApplication();
		$user   = Factory::getUser();
		$option = $app->input->getCmd( 'option' );
		$view   = $app->input->getCmd( 'view' );
		$pk     = 0;

		switch ( $profileApp )
		{
			case 'sociable':
				if ( $option == 'com_sociable' && $view == 'profile' )
				{
					$pk = $app->input->getInt( 'uId', $user->id );
				}
				break;

			case 'cjforum':
				if ( $option == 'com_cjforum' && $view == 'profile' )
				{
					$pk = $app->input->getInt( 'uId', $user->id );
				}
				break;

			case 'easysocial':
				if ( $option == 'com_easysocial' && $view == 'profile' )
				{
					$pk = $app->input->getInt( 'id', $user->id );
				}
				break;

			case 'easyprofile':
				if ( $option == 'com_jsn' && $view == 'profile' )
				{
					$pk = $app->input->getInt( 'id', $user->id );
				}
				break;

			case 'jomsocial':
				if ( $option == 'com_community' && $view == 'profile' )
				{
					$pk = $app->input->getInt( 'userid', $user->id );
				}
				break;

			case 'cb':
				if ( $option == 'com_comprofiler' && $view == 'userprofile' )
				{
					$pk = $app->input->getInt( 'user', $user->id );
				}
				break;

			case 'kunena':
				if ( $option == 'com_kunena' && $view == 'user' )
				{
					$pk = $app->input->getInt( 'userid', $user->id );
				}
				break;

			default:
				$pk = $app->input->getInt( 'id' );
				break;
		}

		return $pk ? $pk : $default;
	}

	/**
	 * Function to prefetch users of selected profile/avatar component
	 *
	 * @param   string  $system  the profile/avatar component to use
	 * @param   mixed   $ids     int/array of user ids to load
	 */
	public function prefetchUserProfiles( $system, $ids ) {

		if ( empty( $ids ) )
		{
			return;
		}
		$ids = array_unique( $ids );

		switch ( $system )
		{
			case 'sociable':

				$api = JPATH_ROOT . '/components/com_sociable/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$api = SociableApi::getProfileApi();
					$api->load( $ids );
				}
				break;

			case 'cjforum':

				$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$api = CjForumApi::getProfileApi();
					$api->load( $ids );
				}
				break;

			case 'cjblog':

				$api = JPATH_ROOT . '/components/com_cjblog/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$api = CjBlogApi::getProfileApi();
					$api->load( $ids );
				}
				break;

			case 'kunena':

				if ( $this->_initialize_kunena() )
				{
					KunenaUserHelper::loadUsers( $ids );
				}
				break;

			case 'cb':

				$api = JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';

				if ( ! is_file( $api ) )
				{
					return;
				}
				require_once( $api );

				cbimport( 'cb.database' );
				cbimport( 'cb.tables' );
				cbimport( 'language.front' );
				cbimport( 'cb.tabs' );
				cbimport( 'cb.field' );
				global $ueConfig;

				CBuser::advanceNoticeOfUsersNeeded( $ids );
				break;

		}
	}

	public function getUserPoints( $pointsApp, $userId ) {
		if ( ! $userId )
		{
			return 0;
		}

		switch ( $pointsApp )
		{
			case 'rewardify':
				$profile = Factory::getApplication()->bootComponent( 'com_rewardify' )->getUserPoints()->getUserProfile( $userId );
				if ( ! empty( $profile->points ) )
				{
					return $profile->points;
				}
				break;

			case 'sociable':

				$api = JPATH_ROOT . '/components/com_sociable/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$profileApi = SociableApi::getProfileApi();
					$profile    = $profileApi->getUserProfile( $userId );

					if ( ! empty( $profile ) )
					{
						return $profile['points'];
					}
				}
				break;

			case 'cjforum':

				$api = JPATH_ROOT . '/components/com_cjforum/lib/api.php';
				if ( file_exists( $api ) )
				{
					require_once $api;
					$profileApi = CjForumApi::getProfileApi();
					$profile    = $profileApi->getUserProfile( $userId );

					if ( ! empty( $profile ) )
					{
						return $profile['points'];
					}
				}
				break;

			case 'cjblog':

				if ( file_exists( JPATH_ROOT . '/components/com_cjblog/lib/api.php' ) )
				{
					include_once JPATH_ROOT . '/components/com_cjblog/lib/api.php';
					$profileApi = CjBlogApi::getProfileApi();
					$profile    = $profileApi->getUserProfile( $userId );

					if ( ! empty( $profile ) )
					{
						return $profile['points'];
					}
				}
				elseif ( file_exists( JPATH_ROOT . '/components/com_cjblog/api.php' ) ) // legacy compatibility
				{
					include_once JPATH_ROOT . '/components/com_cjblog/api.php';
					$profile = CjBlogApi::get_user_profile( $userId );

					if ( ! empty( $profile ) )
					{
						return $profile['points'];
					}
				}

				break;

			case 'aup':

				$api_AUP = JPATH_SITE . '/components/com_alphauserpoints/helper.php';

				if ( file_exists( $api_AUP ) )
				{
					require_once( $api_AUP );
					$profile = AlphaUserPointsHelper::getUserInfo( '', $userId );

					if ( ! empty( $profile ) )
					{
						return $profile->points;
					}
				}

				break;

			case 'atup':

				$api_AUP = JPATH_SITE . '/components/com_altauserpoints/helper.php';

				if ( file_exists( $api_AUP ) )
				{
					require_once( $api_AUP );
					$profile = AltaUserPointsHelper::getUserInfo( '', $userId );

					if ( ! empty( $profile ) )
					{
						return $profile->points;
					}
				}

				break;

			case 'jomsocial':

				$db    = Factory::getDbo();
				$query = 'select points from #__community_users where userid=' . ( (int) $userId );
				$db->setQuery( $query );

				return (int) $db->loadResult();

			case 'easysocial':

				require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
				$my     = Foundry::user( $userId );
				$points = $my->getPoints();

				return $points;

			default:

				return 0;
		}

		return - 1;
	}

	/**
	 * A private function that is used to initialize kunena app
	 *
	 * @return boolean true if success, false if not compatible kunena installation found
	 */
	private function _initialize_kunena() {
		if ( ! ( class_exists( 'KunenaForum' ) && KunenaForum::isCompatible( '2.0' ) && KunenaForum::installed() ) )
		{
			return false;
		}

		KunenaForum::setup();

		return true;
	}

}