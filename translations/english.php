<?php

function REQUIRED_FIELDS_MISSING()
{
  return array(
    'status' => array(
      'name'=>'error',
      'id'=>'700',
      'message' => 'Required fields missing'
    )
  );
}

function USER_DOES_NOT_EXIST($user)
{
  return array(
    
    'status' => array(
      'name'=>'error',
      'id'=>'701',
      'message' => 'User does not exist'
    ),
    'user' => $user
  );
}

function PASSWORD_WRONG($user)
{
  return array(
    'status' => array(
      'name'=>'error',
      'id'=>'702',
      'message' => 'Given password is wrong'
    ),
    'user' => $user
  );
}

function USER_ID_NOT_FOUND()
{
  return array(
    
    'status' => array(
      'name'=>'error',
      'id'=>'703',
      'message' => 'ID not found for user'
    )
  );
}

function UNEXPECTED_ERROR()
{
  return array(
    
    'status' => array(
      'name'=>'error',
      'id'=>'900',
      'message' => 'Unexpected error occurred, contact administration...'
    )
  );
}

function SUCCESS()
{
  return array(
    'status' => array(
      'name'=>'OK',
      'id'=>'0',
      'message' => 'operation successful'
    )
  );
}

?>