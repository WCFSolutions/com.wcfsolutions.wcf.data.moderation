<?php
/*
 * @author	Sebastian Oettl
 * @copyright	2009-2010 WCF Solutions <http://www.wcfsolutions.com/index.php>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */		
/**
 * Deletes the given files.
 *
 * @param	array		$files
 */
function deleteFiles(Package $package, $files) {
	// delete database entries
	$sql = "DELETE FROM	wcf".WCF_N."_package_installation_file_log
		WHERE		filename IN ('".implode("','", array_map('escapeString', $files))."')
				AND packageID = ".$package->getPackageID();
	WCF::getDB()->sendQuery($sql);
	
	// delete files
	foreach ($files as $file) {
		@unlink(RELATIVE_WCF_DIR.$package->getDir().$file);
	}
}

// get deletable files
$deletableFiles = array(
	'icon/moderationControlPanelL.png',
	'icon/moderationControlPanelM.png',
	'icon/moderationControlPanelS.png',
	'lib/page/util/menu/ModerationMenu.class.php',
	'lib/page/util/menu/ModerationMenuContainer.class.php',
	'lib/system/cache/CacheBuilderModerationMenu.class.php'
);

// delete files
deleteFiles($this->installation->getPackage(), $deletableFiles);

// delete pip
$sql = "DELETE FROM	wcf".WCF_N."_package_installation_plugin
	WHERE		packageID = ".$this->installation->getPackageID()."
			AND pluginName = 'ModerationMenuPackageInstallationPlugin'";
WCF::getDB()->sendQuery($sql);
@unlink(WCF_DIR.'lib/acp/package/plugin/ModerationMenuPackageInstallationPlugin.class.php');
?>