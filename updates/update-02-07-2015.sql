ALTER TABLE  `TopicalTags` ADD  `access` BOOLEAN NOT NULL AFTER  `type` ,
ADD  `participation` INT( 1 ) NOT NULL AFTER  `access` ,
ADD  `permanent` BOOLEAN NOT NULL AFTER  `participation` ,
ADD  `inceptive` BOOLEAN NOT NULL AFTER  `permanent` ,
ADD  `special` BOOLEAN NOT NULL AFTER  `inceptive` ;
