DROP TABLE IF EXISTS wcf1_moderationcp_menu_item;
CREATE TABLE wcf1_moderationcp_menu_item (
	menuItemID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	menuItem VARCHAR(255) NOT NULL DEFAULT '',
	parentMenuItem VARCHAR(255) NOT NULL DEFAULT '',
	menuItemLink VARCHAR(255) NOT NULL DEFAULT '',
	menuItemIcon VARCHAR(255) NOT NULL DEFAULT '',
	showOrder INT(10) NOT NULL DEFAULT 0,
	permissions TEXT,
	options TEXT,
	UNIQUE KEY menuItem (menuItem, packageID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_moderation_type;
CREATE TABLE wcf1_moderation_type (
	moderationTypeID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL DEFAULT 0,
	moderationType VARCHAR(255) NOT NULL,
	classFile VARCHAR(255) NOT NULL,
	UNIQUE KEY (packageID, moderationType)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;