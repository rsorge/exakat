<?php
  $s = new SWFShape();
  $f = $s->addFill(0xff, 0, 0);
  $s->setRightFill($f);

  $p = new SWFSprite();
  $m = new SWFMovie();
  $i = $m->add($p);
?>