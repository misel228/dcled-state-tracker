#!/usr/bin/php -q
<?php
/*
 *
 */

define('COMMAND_STRING', 'dcled -p %PRE% %MESSAGE% ');
define('MAX_MESSAGE', 8);
define('MESSAGE_SIZE',128);
define("SHM_IDENTIFIER", ftok('/tmp/shm_test', 'R'));
define('EMPTY_MESSAGE', str_pad(' ', MESSAGE_SIZE, ' '));

$placeholders = array('%PRE%', '%MESSAGE%');

$messages = array (
  1 => '5 mails',
  2 => '10 facebook messages',
  3 => EMPTY_MESSAGE,
  4 => EMPTY_MESSAGE,
  5 => EMPTY_MESSAGE,
  6 => EMPTY_MESSAGE,
  7 => EMPTY_MESSAGE,
  8 => 'oh yeah, oh yeah, oh yeah',
);

$limit = 10;

// var_dump(SHM_IDENTIFIER);

$shm_id = shmop_open(SHM_IDENTIFIER, "w", 0644, MAX_MESSAGE*MESSAGE_SIZE);
// var_dump($shm_id);

if($shm_id !== false){

  foreach($messages as $key => $message) {
    $data = str_pad ( $message , MESSAGE_SIZE, "\0" );
    $shm_bytes_written = shmop_write($shm_id, $data, ($key-1)*MESSAGE_SIZE);
    echo $shm_bytes_written."\n";
  }

  shmop_close($shm_id);
}

