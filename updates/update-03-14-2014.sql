ALTER TABLE  `StaffPermissions` ADD  `site_options` INT( 1 ) NOT NULL ;
UPDATE  `StaffPermissions` SET  `site_options` =  '1' WHERE  `StaffPermissions`.`position_id` =1;