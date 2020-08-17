<?php

function pp($var, $title = false) {
  echo "<pre style='font-size:12px;'>";
  if ($title) {
    echo "<strong>$title</strong></br><hr>";
  }
  print_r($var);
  echo "</pre>";
}

function show_max_char_length(?string $string, ?int $char_length = 70): string {
  $excerpt = strlen($string) > $char_length ? substr($string,0 ,$char_length)."..." : $string;
  $excerpt = strip_tags($excerpt);
  return $excerpt;
}

function calc_date_diff(?object $diff) {
  if(!$diff) {
    return false;
  }

  if($diff->days > 7 && $diff->days < 14) {
    return "1 week ago";
  } else if($diff->days < 7 && $diff->days > 1) {
    return $diff->days . " days ago";
  } else if($diff->days == 1) {
    return "1 day ago";
  } else if($diff->days < 1) {
    return $diff->h. " hours ago";
  } else if($diff->days > 14) {
    return $diff->days . " days ago";
  } else {
    return $diff->days . " days ago";
  }
}
