<?php

function add_value($request)
{

  if($request->isPost())
  {
    echo "Thanks for post!";
    var_dump(json_decode($request->getBody(), true));
  }
}

?>
