<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ModerationCPMenu.class.php');

/**
 * Displays the moderation overview page.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.moderation
 * @subpackage	page
 * @category 	Community Framework
 */
class ModerationCPOverviewPage extends AbstractPage {
	// system
	public $templateName = 'moderationCPOverview';
	
	/**
	 * list of available moderation types
	 * 
	 * @var	array<ModerationType>
	 */
	public $availableModerationTypes = array();
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get available moderation types
		$this->availableModerationTypes = Moderation::getAvailableModerationTypes();
		
		// remove unaccessible moderation types
		foreach ($this->availableModerationTypes as $key => $moderationType) {
			if (!$moderationType->isModerator() || !$moderationType->isAccessible()) {
				unset($this->availableModerationTypes[$key]);
			}
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'moderationTypes' => $this->availableModerationTypes
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		if (!Moderation::isModerator()) {
			throw new PermissionDeniedException();
		}

		// set active menu item
		ModerationCPMenu::getInstance()->setActiveMenuItem('wcf.moderation.menu.link.overview');
		
		// show page
		parent::show();
	}
}
?>