<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the moderation types.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	system.cache
 * @category 	Community Framework
 */
class CacheBuilderModerationTypes implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();
		
		// get moderation type ids
		$typeIDArray = array();
		$sql = "SELECT		moderationType, moderationTypeID 
			FROM		wcf".WCF_N."_moderation_type moderation_type,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		moderation_type.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$typeIDArray[$row['moderationType']] = $row['moderationTypeID'];
		}
		
		// get moderation types
		if (count($typeIDArray) > 0) {
			$sql = "SELECT		moderation_type.*, package.packageDir
				FROM		wcf".WCF_N."_moderation_type moderation_type
				LEFT JOIN	wcf".WCF_N."_package package
				ON		(package.packageID = moderation_type.packageID)
				WHERE		moderation_type.moderationTypeID IN (".implode(',', $typeIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$row['className'] = StringUtil::getClassName($row['classFile']);
				$data[] = $row;
			}
		}
		
		return $data;
	}
}
?>