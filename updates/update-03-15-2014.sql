ALTER TABLE  `InviteTree` ADD  `transaction_id` INT( 11 ) NULL AFTER  `created` ;
ALTER TABLE  `Sessions` ADD  `x_forwarded_for` VARCHAR( 16 ) NULL AFTER  `ip` ;