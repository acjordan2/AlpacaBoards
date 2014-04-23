<?php
/*
 * Link.class.php
 * 
 * Copyright (c) 2014 Andrew Jordan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject to 
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class ConnectionFactory
{
    /**
     * Database connection object
     * @var db_connection
     */
    private $_db;

    /**
     * Database server type (eg, MySQL)
     * @var string
     */
    private $_db_type = DATABASE_TYPE;

    /**
     * Database user
     * @var string
     */
    private $_db_user = DATABASE_USER;

    /**
     * Database password
     * @var string
     */
    private $_db_pass = DATABASE_PASS;

    /**
     * Database host
     * @var string
     */
    private $_db_host = DATABASE_HOST;

    /**
     * Database name
     * @var string
     */
    private $_db_name = DATABASE_NAME;

    /**
     * Current connection factory instance
     * @var ConnectionFactory
     */
    private static $_factory;

    /**
     * Return factory if one already exists
     * @return ConnectionFactory Existing factory
     */
    public static function getInstance()
    {
        // If factory does not exist, create one
        if (!self::$_factory) {
            self::$_factory = new ConnectionFactory();
        }
        return self::$_factory;
    }

    /**
     * Get a database connection object
     * @return mixed
     */
    public function getConnection()
    {
        // Instaniate database connection if one does not exist
        if (!$this->_db) {
            try {
                $this->_db = new PDO(
                    $this->_db_type.":host=".$this->_db_host.";dbname=".$this->_db_name,
                    $this->_db_user,
                    $this->_db_pass
                );
                $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->_db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            } catch (PDOException $e) {
                return false;
            }
        }
        return $this->_db;
    }
}
