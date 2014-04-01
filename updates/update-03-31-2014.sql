ALTER TABLE  `StaffPermissions` ADD  `title` VARCHAR( 32 ) NOT NULL AFTER  `position_id` ;
ALTER TABLE  `StaffPermissions` ADD  `title_color` VARCHAR( 12 ) NOT NULL AFTER  `title` ;