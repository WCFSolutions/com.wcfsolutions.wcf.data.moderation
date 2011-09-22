<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * This PIP installs, updates or deletes moderation cp menu items.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	acp.package.plugin
 * @category 	Community Framework
 */
class ModerationCPMenuPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	public $tagName = 'moderationcpmenu';
	public $tableName = 'moderationcp_menu_item';
	
	/** 
	 * @see PackageInstallationPlugin::install()
	 */
	public function install() {
		parent::install();
		
		if (!$xml = $this->getXML()) {
			return;
		}
		
		// Create an array with the data blocks (import or delete) from the xml file.
		$moderationcpMenuXML = $xml->getElementTree('data');
		
		// Loop through the array and install or uninstall moderationcp-menu items.
		foreach ($moderationcpMenuXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through moderationcp-menu items and create or update them.
					foreach ($block['children'] as $moderationcpMenuItem) {
						// Extract item properties.
						foreach ($moderationcpMenuItem['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$moderationcpMenuItem[$child['name']] = $child['cdata'];
						}
					
						// check required attributes
						if (!isset($moderationcpMenuItem['attrs']['name'])) {
							throw new SystemException("Required 'name' attribute for moderationcp menu item is missing", 13023);
						}
						
						// default values
						$menuItemLink = $parentMenuItem = $menuItemIcon = $permissions = $options = '';
						$showOrder = null;
						
						// get values
						$menuItem = $moderationcpMenuItem['attrs']['name'];
						if (isset($moderationcpMenuItem['link'])) $menuItemLink = $moderationcpMenuItem['link'];
						if (isset($moderationcpMenuItem['parent'])) $parentMenuItem = $moderationcpMenuItem['parent'];
						if (isset($moderationcpMenuItem['icon'])) $menuItemIcon = $moderationcpMenuItem['icon'];
						if (isset($moderationcpMenuItem['showorder'])) $showOrder = intval($moderationcpMenuItem['showorder']);
						$showOrder = $this->getShowOrder($showOrder, $parentMenuItem, 'parentMenuItem');
						if (isset($moderationcpMenuItem['permissions'])) $permissions = $moderationcpMenuItem['permissions'];
						if (isset($moderationcpMenuItem['options'])) $options = $moderationcpMenuItem['options'];
						
						// If a parent link was set and this parent is not in database 
						// or it is a link from a package from other package environment: don't install further.
						if (!empty($parentMenuItem)) {
							$sql = "SELECT	COUNT(*) AS count
								FROM 	wcf".WCF_N."_moderationcp_menu_item
								WHERE	menuItem = '".escapeString($parentMenuItem)."'";
							$menuItemCount = WCF::getDB()->getFirstRow($sql);
							if ($menuItemCount['count'] == 0) {
								throw new SystemException("For the menu item '".$menuItem."' no parent item '".$parentMenuItem."' exists.", 13011);
							}
						}
						
						// Insert or update items. 
						// Update through the mysql "ON DUPLICATE KEY"-syntax. 
						$sql = "INSERT INTO			wcf".WCF_N."_moderationcp_menu_item
											(packageID, menuItem, parentMenuItem, menuItemLink, menuItemIcon, showOrder, permissions, options)
							VALUES				(".$this->installation->getPackageID().",
											'".escapeString($menuItem)."',
											'".escapeString($parentMenuItem)."',
											'".escapeString($menuItemLink)."',
											'".escapeString($menuItemIcon)."',
											".$showOrder.",
											'".escapeString($permissions)."',
											'".escapeString($options)."')
							ON DUPLICATE KEY UPDATE 	parentMenuItem = VALUES(parentMenuItem),
											menuItemLink = VALUES(menuItemLink),
											menuItemIcon = VALUES(menuItemIcon),
											showOrder = VALUES(showOrder),
											permissions = VALUES(permissions),
											options = VALUES(options)";
						WCF::getDB()->sendQuery($sql);
					}
				}
				// Handle the delete instructions.
				else if ($block['name'] == 'delete') {
					if ($this->installation->getAction() == 'update') {
						// Loop through moderationcp-menu items and delete them.
						$itemNames = '';
						foreach ($block['children'] as $moderationcpMenuItem) {
							// check required attributes
							if (!isset($moderationcpMenuItem['attrs']['name'])) {
								throw new SystemException("Required 'name' attribute for 'moderationcpmenuitem'-tag is missing.", 13023);
							}
							// Create a string with all item names which should be deleted (comma seperated).
							if (!empty($itemNames)) $itemNames .= ',';
							$itemNames .= "'".escapeString($moderationcpMenuItem['attrs']['name'])."'";
						}
						// Delete items.
						if (!empty($itemNames)) {
							$sql = "DELETE FROM	wcf".WCF_N."_moderationcp_menu_item
								WHERE		menuItem IN (".$itemNames.")
										AND packageID = ".$this->installation->getPackageID();
							WCF::getDB()->sendQuery($sql);
						}
					}
				}
			}
		}
	}
}
?>