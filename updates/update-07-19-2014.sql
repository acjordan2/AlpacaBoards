ALTER TABLE  `SiteOptions` ADD  `domain` VARCHAR( 256 ) NULL AFTER  `sitename` ;
ALTER TABLE  `SiteOptions` ADD UNIQUE (
`sitename`
);