ALTER TABLE  `TopicalTags` ADD  `user_id` INT( 11 ) UNSIGNED NOT NULL ,
ADD  `created` INT NOT NULL ;


ALTER TABLE  `StaffPermissions` ADD  `tag_create` INT( 1 ) UNSIGNED NOT NULL ,
ADD  `tag_edit` INT( 1 ) NOT NULL ;

UPDATE  `StaffPermissions` SET  `tag_create` =  '1',
`tag_edit` =  '1' WHERE  `StaffPermissions`.`position_id` =1;