#!/usr/bin/php -q
<?php
/*
 * DCLed - state tracker client
 * Takes slot and message as command line argument and writes it in the memory for the tracker to display (TODO)
 *
 */

require 'config.php';

$messages = array (
  1 => '_____',
  2 => EMPTY_MESSAGE,
  3 => 'EM___',
  4 => EMPTY_MESSAGE,
  5 => EMPTY_MESSAGE,
  6 => EMPTY_MESSAGE,
  7 => EMPTY_MESSAGE,
  8 => EMPTY_MESSAGE,
);

// var_dump(SHM_IDENTIFIER);

$shm_id = shmop_open(SHM_IDENTIFIER, "w", 0644, MAX_MESSAGE*MESSAGE_SIZE);
// var_dump($shm_id);

if($shm_id !== false){

  foreach($messages as $key => $message) {
    #first write "empty message" to clear the old message, then write new message
    $shm_bytes_written = shmop_write($shm_id, EMPTY_MESSAGE, ($key-1)*MESSAGE_SIZE);
    $shm_bytes_written = shmop_write($shm_id, $message, ($key-1)*MESSAGE_SIZE);
    echo $shm_bytes_written."\n";
  }

  shmop_close($shm_id);
}

