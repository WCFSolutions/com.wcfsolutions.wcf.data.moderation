<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * This PIP installs, updates or deletes moderation types.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	acp.package.plugin
 * @category 	Community Framework
 */
class ModerationTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	public $tagName = 'moderationtype';
	public $tableName = 'moderation_type';
	
	/** 
	 * @see PackageInstallationPlugin::install()
	 */
	public function install() {
		parent::install();
		
		if (!$xml = $this->getXML()) {
			return;
		}
		
		// Create an array with the data blocks (import or delete) from the xml file.
		$moderationTypeXML = $xml->getElementTree('data');
		
		// Loop through the array and install or uninstall items.
		foreach ($moderationTypeXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through items and create or update them.
					foreach ($block['children'] as $moderationType) {
						// Extract item properties.
						foreach ($moderationType['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$moderationType[$child['name']] = $child['cdata'];
						}
					
						// default values
						$name = $classFile = '';
						
						// get values
						if (isset($moderationType['name'])) $name = $moderationType['name'];
						if (isset($moderationType['classfile'])) $classFile = $moderationType['classfile'];
						
						// insert items
						$sql = "INSERT INTO			wcf".WCF_N."_moderation_type
											(packageID, moderationType, classFile)
							VALUES				(".$this->installation->getPackageID().",
											'".escapeString($name)."',
											'".escapeString($classFile)."')
							ON DUPLICATE KEY UPDATE 	classFile = VALUES(classFile)";
						WCF::getDB()->sendQuery($sql);
					}
				}
				// Handle the delete instructions.
				else if ($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					// Loop through items and delete them.
					$nameArray = array();
					foreach ($block['children'] as $moderationType) {
						// Extract item properties.
						foreach ($moderationType['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$moderationType[$child['name']] = $child['cdata'];
						}
					
						if (empty($moderationType['name'])) {
							throw new SystemException("Required 'name' attribute for moderation type is missing", 13023); 
						}
						$nameArray[] = $moderationType['name'];
					}
					if (count($nameArray)) {
						$sql = "DELETE FROM	wcf".WCF_N."_moderation_type
							WHERE		packageID = ".$this->installation->getPackageID()."
									AND moderationType IN ('".implode("','", array_map('escapeString', $nameArray))."')";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
	}
}
?>