<?php
// wcf imports
require_once(WCF_DIR.'lib/data/moderation/type/ModerationType.class.php');

/**
 * Provides default implementations for moderation types.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	data.moderation.type
 * @category 	Community Framework
 */
abstract class AbstractModerationType implements ModerationType {
	/**
	 * @see ModerationType::getName()
	 */
	public function getName() {
		return '';
	}
	
	/**
	 * @see ModerationType::getIcon()
	 */	
	public function getIcon() {
		return '';
	}

	/**
	 * @see ModerationType::getURL()
	 */	
	public function getURL() {
		return '';
	}
	
	/**
	 * @see ModerationType::isImportant()
	 */	
	public function isImportant() {
		return false;
	}

	/**
	 * @see ModerationType::isModerator()
	 */
	public function isModerator() {
		return false;
	}
	
	/**
	 * @see	ModerationType::isAccessible()
	 */
	public function isAccessible() {
		return true;
	}
	
	/**
	 * @see ModerationType::getOutstandingModerations()
	 */
	public function getOutstandingModerations() {
		return 0;
	}
}
?>