Sper.gs Messaging Board
=======================

##REQUIREMENTS
* WebServer running PHP 5.3.7+
* php5-curl (for ETI username check)
* php5-json
* php5-gd or php5-imagick
* php5-mcrypt
* MySQL (Other databases might work but they are untested)
* Sphinx Search (For full text search)
* A mail server such as postfix (for sending out invites and password reset)

##Install Directions
1. Put contents of www in your webroot
2. Navigate to the install directory with your browser and run the installer
3. Edit Sphinx varibles to match your environment in www/includes/Config.ini.php and sphinx/sphinx.conf
4. Start the sphinx search daemon by navigating to the sphinx directory and running "php start_sphinx.php". Creating a better way to do this is on my "to-do" list.

###Notes:
	* Make sure to turn off magic quotes in your php.ini as it causes unexpected behavior. 
	* This has only been tested on linux.
