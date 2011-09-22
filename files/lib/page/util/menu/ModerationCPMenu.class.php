<?php
// wcf imports
require_once(WCF_DIR.'lib/page/util/menu/TreeMenu.class.php');

/**
 * Builds the moderation control panel menu.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	page.util.menu
 * @category 	Community Framework
 */
class ModerationCPMenu extends TreeMenu {
	protected static $instance = null;

	/**
	 * Returns an instance of the ModerationCPMenu class.
	 * 
	 * @return	ModerationCPMenu
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new ModerationCPMenu();
		}
		
		return self::$instance;
	}
	
	/**
	 * @see TreeMenu::loadCache()
	 */
	protected function loadCache() {
		parent::loadCache();
		
		WCF::getCache()->addResource('moderationCPMenu-'.PACKAGE_ID, WCF_DIR.'cache/cache.moderationCPMenu-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderModerationCPMenu.class.php');
		$this->menuItems = WCF::getCache()->get('moderationCPMenu-'.PACKAGE_ID);
	}
	
	/**
	 * @see TreeMenu::parseMenuItemLink()
	 */
	protected function parseMenuItemLink($link, $path) {
		if (preg_match('~\.php$~', $link)) {
			$link .= SID_ARG_1ST; 
		}
		else {
			$link .= SID_ARG_2ND_NOT_ENCODED;
		}
		
		return $link;
	}
	
	/**
	 * @see TreeMenu::parseMenuItemIcon()
	 */
	protected function parseMenuItemIcon($icon, $path) {
		return StyleManager::getStyle()->getIconPath($icon);
	}
}
?>