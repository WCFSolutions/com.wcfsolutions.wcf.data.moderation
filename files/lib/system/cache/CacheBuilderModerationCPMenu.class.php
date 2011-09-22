<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the moderation control panel menu item tree.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	system.cache
 * @category 	Community Framework
 */
class CacheBuilderModerationCPMenu implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();

		// get all menu items and filter menu items with low priority
		$sql = "SELECT		menuItem, menuItemID 
			FROM		wcf".WCF_N."_moderationcp_menu_item menu_item,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		menu_item.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		$itemIDs = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$itemIDs[$row['menuItem']] = $row['menuItemID'];
		}
		
		if (count($itemIDs) > 0) {
			// get needed menu items and build item tree
			$sql = "SELECT		menuItem, parentMenuItem, menuItemLink,
						menuItemIcon, permissions, options, packageDir
				FROM		wcf".WCF_N."_moderationcp_menu_item menu_item
				LEFT JOIN	wcf".WCF_N."_package package
				ON		(package.packageID = menu_item.packageID)
				WHERE		menuItemID IN (".implode(',', $itemIDs).")
				ORDER BY	showOrder";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if (!isset($data[$row['parentMenuItem']])) {
					$data[$row['parentMenuItem']] = array();
				}
				
				$data[$row['parentMenuItem']][] = $row;
			}
		}
		
		return $data;
	}
}
?>