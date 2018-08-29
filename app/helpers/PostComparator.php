<?php
class PostComparator {
 
  //compares the files and orders in descending order by last modification time
  public static function cmp($a, $b) {
    if ($a->getMTime() == $b->getMTime()) {
      return 0;
    }
    return ($a->getMTime() > $b->getMTime()) ? -1 : 1;
  }
}
?>
