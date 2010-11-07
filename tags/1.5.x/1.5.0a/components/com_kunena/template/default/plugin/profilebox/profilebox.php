<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2009 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*
* Based on FireBoard Component
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/

// Dont allow direct linking
defined( '_JEXEC' ) or die('Restricted access');

global $fbConfig;
$my = &JFactory::getUser();
$database = &JFactory::getDBO();
//first we gather some information about this person
$database->setQuery("SELECT su.view, u.name, su.moderator,su.avatar FROM #__fb_users as su"
                    . "\nLEFT JOIN #__users as u on u.id=su.userid WHERE su.userid={$my->id} LIMIT 1");

$_user = $database->loadObject();

$prefview = $_user->view;
$username = $_user->name; // externally used  by fb_pathway, myprofile_menu
$moderator = $_user->moderator;
$fbavatar = $_user->avatar;
$jr_username = $_user->name;

$jr_avatar = '';
if ($fbConfig->avatar_src == "jomsocial")
{
	// Get CUser object
	$jsuser =& CFactory::getUser($my->id);
    $jr_avatar = '<img src="' . $jsuser->getThumbAvatar() . '" alt=" " />';
}
else if ($fbConfig->avatar_src == "clexuspm")
{
    $jr_avatar = '<img src="' . MyPMSTools::getAvatarLinkWithID($my->id) . '" alt=" " />';
}
else if ($fbConfig->avatar_src == "cb")
{
	$jr_avatar = $kunenaProfile->showAvatar($my->id);
}
else
{
    if ($fbavatar != "") {
		if(!file_exists(KUNENA_PATH_UPLOADED .DS. 'avatars/s_' . $fbavatar)) {
            $jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/' . $fbavatar . '" alt=" " />';
		} else {
		  $jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/s_' . $fbavatar . '" alt=" " />';
		}
    }
    else {
 		$jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/s_nophoto.jpg" alt=" " />';
        $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile&amp;do=show') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
    }

}

if ($fbConfig->fb_profile == "cb" || $fbConfig->fb_profile == "jomsocial")
{
    $jr_profilelink = CKunenaLink::GetProfileLink($fbConfig, $my->id, _PROFILEBOX_MYPROFILE);
}
else if ($fbConfig->fb_profile == "clexuspm") {
    $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile&amp;do=show') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
}
else
{
    $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile&amp;do=show') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
}

$jr_myposts = '<a href="' . JRoute::_(KUNENA_LIVEURLREL .  '&amp;func=showauthor&amp;task=showmsg&amp;auth=' . $my->id . '') . '" >' . _PROFILEBOX_SHOW_MYPOSTS . '</a>';
$jr_latestpost = JRoute::_(KUNENA_LIVEURLREL . '&amp;func=latest');
?>

<?php // AFTER LOGIN AREA
if ($fbConfig->fb_profile == 'cb')
{
    $loginlink = JRoute::_('index.php?option=com_comprofiler&amp;task=login');
    $logoutlink = JRoute::_('index.php?option=com_comprofiler&amp;task=logout');
    $registerlink = JRoute::_('index.php?option=com_comprofiler&amp;task=registers');//.KUNENA_CB_ITEMID_SUFFIX);
    $lostpasslink = JRoute::_('index.php?option=com_comprofiler&amp;task=lostPassword');//.KUNENA_CB_ITEMID_SUFFIX);
}
else
{
	$loginlink = JRoute::_('index.php?option=com_user&amp;view=login');
	$logoutlink = JRoute::_('index.php?option=com_user&amp;view=login');
	$registerlink = JRoute::_('index.php?option=com_user&amp;task=register&amp;Itemid=' . $Itemid);
	$lostpasslink = JRoute::_('index.php?option=com_user&amp;view=reset&amp;Itemid=' . $Itemid);
}

if ($my->id)
{
?>

    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "fb_profilebox" >
        <tbody id = "topprofilebox_tbody">
            <tr class = "<?php echo $boardclass ;?>sectiontableentry1">
                <td  class = "td-1  fbm" align="left" width="5%">
<?php echo CKunenaLink::GetProfileLink($fbConfig, $my->id, $jr_avatar);?>
                </td>

                <td valign = "top" class = "td-2  fbm fb_profileboxcnt" align="left">
<?php echo _PROFILEBOX_WELCOME; ?>, <b><?php echo $jr_username; ?></b>

                <br />

                <a href = "<?php echo $jr_latestpost ; ?>"><?php
    echo _PROFILEBOX_SHOW_LATEST_POSTS; ?> </a> | <?php echo $jr_profilelink; ?> |  <a href = "<?php echo $logoutlink;?>"><?php echo _PROFILEBOX_LOGOUT; ?></a>
<?php
$user_fields = @explode(',', $fbConfig->annmodid);

if (in_array($my->id, $user_fields) || $my->usertype == 'Administrator' || $my->usertype == 'Super Administrator') {
    $is_editor = true;
}
else {
    $is_editor = false;
}

if ($is_editor) {
$annlink = 'index.php?option=com_kunena&amp;func=announcement&amp;do=show'.KUNENA_COMPONENT_ITEMID_SUFFIX;

?>
| <a href = "<?php echo $annlink;?>"><?php echo _ANN_ANNOUNCEMENTS; ?> </a>
<?php } ?>

</td>
<jdoc:exists type="modules" condition="kunena_profilebox">
	<td>
		<div class = "fb_profilebox_modul">
			<jdoc:include type="modules" name="kunena_profilebox" />
		</div>
	</td>
</jdoc:exists>
            </tr>
        </tbody>
    </table>

    <?php
}
else
{
    // LOGOUT AREA
    ?>

    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0"  class = "fb_profilebox">
        <tbody id = "topprofilebox_tbody">
            <tr class = "<?php echo $boardclass ;?>sectiontableentry1">
                <td valign = "top" class = "td-1  fbm fb_profileboxcnt" align="left">
<?php echo _PROFILEBOX_WELCOME; ?>, <b><?php echo _PROFILEBOX_GUEST; ?></b>

                <br/> <?php echo _PROFILEBOX_PLEASE; ?>

                <a href = "<?php echo $loginlink;?>"><?php echo _PROFILEBOX_LOGIN; ?></a> <?php echo _PROFILEBOX_OR; ?> <a href = "<?php echo $registerlink;?>"><?php echo _PROFILEBOX_REGISTER; ?></a>.

                &nbsp;&nbsp;

                <a href = "<?php echo $lostpasslink;?>"><?php echo _PROFILEBOX_LOST_PASSWORD; ?></a>

</td>
<jdoc:exists type="modules" condition="kunena_profilebox">
	<td>
		<div class = "fb_profilebox_modul">
			<jdoc:include type="modules" name="kunena_profilebox" />
		</div>
	</td>
</jdoc:exists>
            </tr>
        </tbody>
    </table>

<?php
}
?>