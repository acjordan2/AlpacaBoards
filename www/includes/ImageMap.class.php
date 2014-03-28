<?php
/*
 * ImageMap.class.php
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

class ImageMap
{
    
    private $pdo_conn;

    public function __construct(&$db)
    {
        $this->pdo_conn = $db;
    }

    public function getImageMapByHash($hash)
    {
        //$sql = "SELECT ImageMap.topic_id, ImageMap.image_id 
        //    FROM UploadedImages LEFT JOIN ImageMap using(image_id) WHERE UploadedImages.image_id = 1";
        
        $sql = "SELECT ImageMap.topic_id FROM UploadedImages LEFT JOIN ImageMap using(image_id) WHERE sha1_sum = ?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($hash));
        $topic_id_array = $statement->fetchAll();
        $topic_id_var = "ImageMap.topic_id=".$topic_id_array[0][0];
        if (sizeof($topic_id_array) > 1) {
            array_shift($topic_id_array);
            foreach($topic_id_array as $topic_id) {
                $topic_id_var .= " OR ImageMap.topic_id=".$topic_id[0];
            }
        }
       
       $sql_getImageMap = "SELECT UploadedImages.sha1_sum, UploadedImages.thumb_height, UploadedImages.thumb_width, Topics.title, ImageMap.topic_id
            FROM Topics LEFT JOIN ImageMap USING(topic_id) LEFT JOIN UploadedImages using(image_id) LEFT JOIN UploadLog using(image_id) WHERE $topic_id_var";
       $statement = $this->pdo_conn->query($sql_getImageMap);
       return $results = $statement->fetchAll();
    }
}
