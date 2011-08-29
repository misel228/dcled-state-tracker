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
  1 => EMPTY_MESSAGE,
  2 => EMPTY_MESSAGE,
  3 => EMPTY_MESSAGE,
  4 => EMPTY_MESSAGE,
  5 => EMPTY_MESSAGE,
  6 => 'w00t',
  7 => 'yeah',
  8 => 'oh',
);

$limit = 10;

// var_dump(SHM_IDENTIFIER);

$shm_id = shmop_open(SHM_IDENTIFIER, "c", 0644, MAX_MESSAGE*MESSAGE_SIZE);
// var_dump($shm_id);

if($shm_id !== false){

  foreach($messages as $key => $message) {
    $shm_bytes_written = shmop_write($shm_id, $message, ($key-1)*MESSAGE_SIZE);
    echo $shm_bytes_written."\n";
  }

  shmop_close($shm_id);
}

$shm_id = shmop_open(SHM_IDENTIFIER, "a", 0644, MAX_MESSAGE*MESSAGE_SIZE);

if($shm_id !== false){

  while(true){
    for($i = 1; $i <= MAX_MESSAGE; $i++) {
      $message = shmop_read($shm_id, ($i-1)*MESSAGE_SIZE, MESSAGE_SIZE);
      if($message != EMPTY_MESSAGE) {
        $replacements = array($i, $message);
        $command = str_replace($placeholders,$replacements, COMMAND_STRING);

        echo $command."\n";
        sleep(1);
      }
    }
  }
}

shmop_delete($shm_id);
shmop_close($shm_id);
