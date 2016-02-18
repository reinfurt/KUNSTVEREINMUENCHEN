<?php
/*
 * this script connects the the twitter REST API for the 
 * account @k_dot_m, retrieves the latest tweet, 
 * appends " @k.m",
 * and stores it in the variable $tweet_text
 */
require "lib/twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$num_tweets = 1;

$consumer_key = "s380aXmqCdcJDPhK2mwGsChue";
$consumer_secret = "pekbKBnSI5SvsdGRmZMTDppiwLGY5u3YoO1TgPn71cMtQ8N9KP";
$access_token = "4113977842-JCvbmYy5ukh65XHJ3a0BAKoNG3rpaxTRKJD2LYx";
$access_token_secret = "hexhvziOe71HbOof7mYYIT6RAX8h034gl98qsQIxS4tDz";

try {
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
	$status = $connection->get("statuses/user_timeline", array("count" => $num_tweets, "exclude_replies" => true));
	$tweet_text = $status[0]->text."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@k_dot_m";
}
catch(Exception $e) {
	$tweet_text = "twitter is down";
}


?>