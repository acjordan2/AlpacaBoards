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

    /** Filename of the parent image for the image map */
    private $filename;

    public function __construct(&$db)
    {
        $this->pdo_conn = $db;
    }

    public function getImageMapByHash($hash)
    {
        
        $sql = "SELECT ImageMap.topic_id, ImageMap.image_id FROM UploadedImages 
            LEFT JOIN ImageMap using(image_id) WHERE sha1_sum = ?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($hash));
        $topic_id_array = $statement->fetchAll();

        if ($statement->rowCount() != 0 && sizeof(array_filter($topic_id_array[0])) > 0) {
            $topic_id_var = "ImageMap.topic_id=".$topic_id_array[0][0];
            if (sizeof($topic_id_array) > 1) {
                array_shift($topic_id_array);
                foreach ($topic_id_array as $topic_id) {
                    $topic_id_var .= " OR ImageMap.topic_id=".$topic_id[0];
                }
            }
           
            $sql_getImageMap = "SELECT Distinct(UploadedImages.sha1_sum), UploadedImages.thumb_height, 
                UploadedImages.thumb_width, UploadedImages.created, Topics.title, ImageMap.topic_id, UploadLog.filename FROM Topics LEFT JOIN 
                ImageMap USING(topic_id) LEFT JOIN UploadedImages using(image_id) LEFT JOIN UploadLog 
                using(image_id) WHERE ($topic_id_var) AND ImageMap.image_id != ".$topic_id_array[0][1].
                " ORDER BY ImageMap.map_id DESC";

            $statement = $this->pdo_conn->query($sql_getImageMap);
            $results = $statement->fetchAll();

            foreach ($results as $key => $value) {
                $results[$key]['title'] = htmlentities($results[$key]['title']);
                $results[$key]['filename'] = htmlentities($results[$key]['filename']);
                $results[$key]['filename_url'] = urlencode($results[$key]['filename']);
                $results[$key]['thumbnail'] = $results[$key]['filename_url'];
                if ($results[$key]['created'] <= 1442629719) {
                    $results[$key]['thumbnail'] .= ".jpg";
                }
            }
            return $results;
        } else {
            return false;
        }
    }

    public function getFileNameFromHash($hash)
    {
        $sql = "SELECT UploadLog.filename From UploadLog LEFT JOIN UploadedImages USING(image_id) 
            WHERE UploadedImages.sha1_sum = ? ORDER BY UploadLog.uploadlog_id ASC LIMIT 1";
        $statement= $this->pdo_conn->prepare($sql);
        $statement->execute(array($hash));
        return $statement->fetch();
    }

    public function getImageMapForUser($user_id)
    {
        $sql_getImageMap = "SELECT DISTINCT(ImageMap.image_id), UploadedImages.sha1_sum, UploadedImages.thumb_height, 
                UploadedImages.thumb_width, UploadedImages.created, Topics.title, ImageMap.topic_id, UploadLog.filename, MAX(ImageMap.image_id) FROM Topics LEFT JOIN 
                ImageMap USING(topic_id) LEFT JOIN UploadedImages using(image_id) LEFT JOIN UploadLog 
                using(image_id) WHERE ImageMap.user_id = $user_id GROUP BY ImageMap.image_id ORDER BY ImageMap.map_id DESC";
         $statement = $this->pdo_conn->query($sql_getImageMap);
        $results = $statement->fetchAll();

        foreach ($results as $key => $value) {
            $results[$key]['title'] = htmlentities($results[$key]['title']);
            $results[$key]['filename'] = htmlentities($results[$key]['filename']);
            $results[$key]['filename_url'] = urlencode($results[$key]['filename']);
            $results[$key]['thumbnail'] = $results[$key]['filename_url'];
            if ($results[$key]['created'] <= 1442629719) {
                $results[$key]['thumbnail'] .= ".jpg";
            }

        }
        return $results;
    }
}
