<?php
/*
 * update.php
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

include "../../www/includes/init.php";

    $sql = "SELECT LinkMessages.message_id, LinkMessages.link_id, 
        LinkMessages.revision_no, LinkMessages.message, LinkMessages.posted,
        LinkMessages.user_id
        FROM LinkMessages ORDER BY link_id DESC";
    $statement = $db->query($sql);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

	print "Moving messages from `LinkMessages` to `Messages`\n";
    foreach ($results as $key) {
        $sql = "INSERT INTO Messages (link_id, revision_no, message, posted, type, user_id)
            VALUES (:link_id, :revision_no, :message, :posted, :message_id, :user_id)";
        $statement = $db->prepare($sql);
        $statement->execute($key);
        $statement->closeCursor();
    }

    $sql = "SELECT Messages.message, Messages.message_id, Messages.link_id FROM Messages WHERE Messages.link_id != 0";
    $statement = $db->query($sql);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    $pattern = "/<quote msgid=\"l,([0-9]),([0-9])@([0-9])\"/";
	print "Updating quotes\n";
    foreach($results as $key) {
        
        if (!is_null($key['link_id'])) {
            preg_match_all($pattern, $key['message'], $matches);
            if (count($matches[0])) {
                $sql = "SELECT Messages.message_id FROM Messages WHERE Messages.link_id = :link_id";
                $statement = $db->prepare($sql);
                $statement->bindParam("link_id", $matches[2][0]);
                $statement->execute();
                $results2 = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();

                $message = $key['message'];

                $message = str_replace($matches[0][0], "<quote msgid=\"l,".$matches[1][0].",".$results2['message_id']."@".$matches[3][0]."\"", $key['message']);

                $sql2 = "UPDATE Messages SET message = :message WHERE message_id = ".$key['message_id'];
                $statement2 = $db->prepare($sql2);
                $statement2->bindParam("message", $message);
                $statement2->execute();
                $statement2->closeCursor();
            } 
        }

    }
	print "Cleaning Up\n";
    $sql = "UPDATE Messages SET type = 0";
    $statement = $db->query($sql);
    $statement->execute();
    $statement->closeCursor();

	print "Done\n";
