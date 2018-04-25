<?php

function gethashtag($array){
  if(isset($array->entities->hashtags) && is_array($array->entities->hashtags)) {
      if(count($array->entities->hashtags)) {
        foreach($array->entities->hashtags as $hashtag) {
            echo '<br>'.$hashtag->text.'<br>';
          }
      }
      else {
        //  echo 'The result is empty';
      }
  }
}

function getRetweet($array){
  echo $array->retweet_count.'<br>';
}

function getFavorite($array){
  echo  $array->favorite_count.'<br>';
}
 function getTweet($array){
   echo $array->text.'<br>';
   extractEmoji($array->text);
 }

function getBatch($tweets){
  $tweetwithemoji = 0;
   if(isset($tweets->statuses) && is_array($tweets->statuses)) {
       if(count($tweets->statuses)) {
           foreach($tweets->statuses as $tweet) {
             if (has_emojis($tweet->text) == true){
               $tweetwithemoji = $tweetwithemoji+1;.
               getTweet($tweet);
               gethashtag($tweet);
               getRetweet($tweet);
               getFavorite($tweet);
             }
           }
           echo "Il y a ".$tweetwithemoji." tweets avec des émojis";
       }

       else {
           echo 'The result is empty';
       }
   }
 }


?>
