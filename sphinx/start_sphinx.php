<?php
require ("../www/includes/Config.ini.php");
shell_exec(INDEXER_PATH." --config ".SPHINX_CONFIG." --all");
print "Indexer Started\n";
shell_exec(SEARCHD_PATH." --config ".SPHINX_CONFIG);
print "Search Daemon Started\n";
?>
