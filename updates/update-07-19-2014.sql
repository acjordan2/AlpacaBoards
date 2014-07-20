ALTER TABLE  `SiteOptions` ADD  `domain` VARCHAR( 256 ) NOT NULL AFTER  `sitename` ;
ALTER TABLE  `SiteOptions` ADD UNIQUE (
`sitename`
);