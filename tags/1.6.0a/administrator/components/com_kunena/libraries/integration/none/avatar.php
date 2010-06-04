<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 *
 **/
//
// Dont allow direct linking
defined( '_JEXEC' ) or die('');

class KunenaAvatarNone
{
	public function __construct() {
		$this->priority = 5;
	}

	public function load($userlist) {}
	public function getEditURL() {}
	protected function _getURL($user, $sizex, $sizey) {}
}
