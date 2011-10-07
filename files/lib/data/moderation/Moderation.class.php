<?php
/**
 * Represents the moderation.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	data.moderation
 * @category 	Community Framework
 */
class Moderation {
	/**
	 * list of available moderation types
	 * 
	 * @var	array<ModerationType>
	 */
	protected static $availableModerationTypes = null;

	/**
	 * Returns the number of outstanding moderations.
	 * 
	 * @return	integer
	 */
	public static function getOutstandingModerations() {
		$outstandingModerations = 0;
		$types = self::getAvailableModerationTypes();
		foreach ($types as $type) {
			if ($type->isImportant() && $type->isModerator()) {
				$outstandingModerations += $type->getOutstandingModerations();
			}
		}
		return $outstandingModerations;
	}
	
	/**
	 * Checks whether the active user is a moderator.
	 *
	 * @return	boolean
	 */
	public static function isModerator() {
		$types = self::getAvailableModerationTypes();
		foreach ($types as $type) {
			if ($type->isModerator()) return true;
		}
		return false;
	}
	
	/**
	 * Returns a list of available moderation types.
	 * 
	 * @return	array<ModerationType>
	 */
	public static function getAvailableModerationTypes() {
		if (self::$availableModerationTypes === null) {
			self::$availableModerationTypes = array();
			WCF::getCache()->addResource('moderationTypes-'.PACKAGE_ID, WCF_DIR.'cache/cache.moderationTypes-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderModerationTypes.class.php');
			$types = WCF::getCache()->get('moderationTypes-'.PACKAGE_ID);
			foreach ($types as $type) {
				// get path to class file
				if (empty($type['packageDir'])) {
					$path = WCF_DIR;
				}
				else {						
					$path = FileUtil::getRealPath(WCF_DIR.$type['packageDir']);
				}
				$path .= $type['classFile'];
				
				// include class file
				if (!class_exists($type['className'])) {
					if (!file_exists($path)) {
						throw new SystemException("Unable to find class file '".$path."'", 11000);
					}
					require_once($path);
				}
				
				// instance object
				if (!class_exists($type['className'])) {
					throw new SystemException("Unable to find class '".$type['className']."'", 11001);
				}
				self::$availableModerationTypes[$type['moderationType']] = new $type['className'];
			}
		}		
		return self::$availableModerationTypes;
	}
}
?>