<?php
/**
 * The core class of applications that uses the moderation control panel menu should implement this interface.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	page.util.menu
 * @category 	Community Framework
 */
interface ModerationCPMenuContainer {
	/**
	 * Returns the active object of the moderation control panel menu.
	 * 
	 * @return	ModerationCPMenu
	 */
	public static function getModerationCPMenu();
}
?>