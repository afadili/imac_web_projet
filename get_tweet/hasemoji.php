<?php

  function has_emojis($str)
  {
      $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{2702}-\x{27B0}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{24C2}-\x{1F251}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{1F680}-\x{1F6C0}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{1F30D}-\x{1F567}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{1F681}-\x{1F6C5}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{1F600}-\x{1F636}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      $regexEmoticons = '/[\x{1F370}-\x{1F38A}]/u';
      preg_match($regexEmoticons, $str, $matches_emo);
      if (!empty($matches_emo[0])) {
          return true;
      }
      return false;
  }

function extractEmoji($str){
  $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{2702}-\x{27B0}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{24C2}-\x{1F251}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{1F680}-\x{1F6C0}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{1F30D}-\x{1F567}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{1F681}-\x{1F6C5}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{1F600}-\x{1F636}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
    print_r($matches_emo[0]);
      return true;
  }
  $regexEmoticons = '/[\x{1F370}-\x{1F38A}]/u';
  preg_match_all($regexEmoticons, $str, $matches_emo);
  if (!empty($matches_emo[0])) {
      print_r($matches_emo[0]);
      return true;
  }
  return false;
}



 ?>
