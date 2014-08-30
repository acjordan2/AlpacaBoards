<?php
/*
 * profile.php
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
 
require "includes/init.php";
require "includes/Parser.class.php";

// Check authentication
if ($auth === true) {
    // Get profile for provided user ID.
    // If no ID is provided, use the ID
    // of the current logged in user.
    $user_id = @$_GET['user'];
    if (is_null($user_id)) {
        $user_id=$authUser->getUserID();
    } else {
        $user_id=$_GET['user'];
    }
    if (is_numeric($user_id)) {
        // Verify the provided user ID is valid
        try {
            $profile_user = new User($site, $user_id);
            // Check account permissions. If
            // account has moderator privelages,
            // allow moderation actions.
            if (isset($_GET['mod_action'])
                && $authUser->checkPermissions($_GET['mod_action'])
            ) {
                $csrf->setPageSalt("profile_moderator");
                $smarty->assign("token", $csrf->getToken());
                $smarty->assign("mod_action", $_GET['mod_action']);

                // Perform actions
                switch ($_GET['mod_action']) {
                    case "user_ban":
                        if ($profile_user->getStatus() == -1) {
                                $message = "User has already been banned!";
                        }
                        $action_taken = "User has been banned";
                        if ($authUser->getUserID() == $profile_user->getUserID()) {
                            $message = "You can't ban youself!";
                        }
                        if (isset($message)) {
                            $smarty->assign("message", $message);
                        } else {
                            if (isset($_POST['submit'])) {
                                if ($csrf->validateToken($_REQUEST['token'])) {
                                    if ($authUser->setStatus(
                                        -1,
                                        $profile_user->getUserID(),
                                        $action_taken,
                                        $_POST['description']
                                    )) {
                                        $message = $profile_user->getUsername()
                                            ." has been banned";
                                        $smarty->assign("message", $message);
                                    }
                                }
                            }
                        }
                        $smarty->assign("p_user_id", $profile_user->getUserID());
                        $smarty->assign("p_username", $profile_user->getUsername());
                        $smarty->assign("action_taken", $action_taken);
                        $page_title = "Ban ".$profile_user->getUsername();
                        break;
                }
                $display = "mod_actions.tpl";
            } else {
                // Get profile data
                $avatar = $profile_user->getAvatar();
                if (!is_null($avatar)) {
                    $smarty->assign(
                        "avatar",
                        $avatar['sha1_sum']."/".
                        urlencode($avatar['filename'])
                    );
                    $smarty->assign("avatar_width", $avatar['width']);
                    $smarty->assign("avatar_height", $avatar['height']);
                    $smarty->assign("sha1_sum", $avatar['sha1_sum']);
                }
                
                // Parse for HTML tags, encode
                // dangerous tags.
                $parser = new Parser($db);
                $signature = substr($profile_user->getSignature(), 4);
                $signature = $parser->parse($signature);

                if ($profile_user->getQuote() != "") {
                    $quote = $profile_user->getQuote();
                    $quote = $parser->parse($quote);
                } else {
                    $quote = "";
                }
                
                // Assign template variables
                $smarty->assign("p_username", $profile_user->getUsername());
                $smarty->assign("p_level", $profile_user->getAccessLevel());
                $smarty->assign("p_user_id", $profile_user->getUserID());
                $smarty->assign("p_status", $profile_user->getStatus());
                $smarty->assign("p_karma", $profile_user->getKarma());
                $smarty->assign("good_karma", $profile_user->getGoodKarma());
                $smarty->assign("bad_karma", $profile_user->getBadKarma());
                $smarty->assign(
                    "contribution_karma",
                    $profile_user->getContributionKarma()
                );
                $smarty->assign("credit", $profile_user->getCredits());
                $smarty->assign("created", $profile_user->getAccountCreated());
                $smarty->assign("last_active", $profile_user->getLastActive());
                $smarty->assign("signature", $signature);
                $smarty->assign("quote", $quote);
                $smarty->assign(
                    "instant_messaging",
                    $authUser->getInstantMessaging()
                );
                $smarty->assign("public_email", $authUser->getEmail());
                
                $smarty->assign("access_level", $authUser->getAccessLevel());
                $smarty->assign("access_title", $authUser->getAccessTitle());
                $smarty->assign(
                    "mod_user_ban",
                    $authUser->checkPermissions("user_ban")
                );
                $smarty->assign(
                    "mod_site_options",
                    $authUser->checkPermissions("site_options")
                );
                $smarty->assign("invite_status", $site->getInviteStatus());

                // Set page template
                $display = "profile.tpl";
                $page_title = "User Profile - ".$profile_user->getUsername();
            }
            include "includes/deinit.php";
        } catch (Exception $e) {
            include "404.php";
        }
    } else {
        include "404.php";
    }
} else {
    include "403.php";
}
