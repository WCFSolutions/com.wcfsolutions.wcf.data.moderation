<?php
/**
 * All moderation types should implement this interface.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	data.moderation.type
 * @category 	Community Framework
 */
interface ModerationType {
	/**
	 * Returns the name of this moderation type.
	 * 
	 * @return	string
	 */
	public function getName();
	
	/**
	 * Returns the icon of this moderation type.
	 * 
	 * @return	string
	 */	
	public function getIcon();
	
	/**
	 * Returns the url of this moderation type.
	 * 
	 * @return	string
	 */
	public function getURL();
	
	/**
	 * Checks whether this moderation type is important or not.
	 * 
	 * @return	boolean
	 */
	public function isImportant();

	/**
	 * Checks whether the active user can moderate this moderation type.
	 * 
	 * @return	boolean
	 */
	public function isModerator();
	
	/**
	 * Returns true, if the moderation type is accessible.
	 *
	 * @return	boolean
	 */
	public function isAccessible();
	
	/**
	 * Returns the number of outstanding moderations of the active user.
	 * 
	 * @return	integer
	 */
	public function getOutstandingModerations();
}
?>