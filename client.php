#!/usr/bin/php -q
<?php
/*
 * DCLed - state tracker client
 * Takes slot and message as command line argument and writes it in the memory for the tracker to display (TODO)
 *
 */

require 'config.php';
#var_dump($argv);die();
# parse command line arguments
if(isset($argv[1]) && $argv[1]=='-h') {
  print_help_message();die();
}

if(!isset($argv[1]) || !isset($argv[2]) || !is_numeric($argv[1])) {
  print_help_message();die();
}

$slot    = $argv[1];
$message = $argv[2];


#die('anyway');
# Init: Open shared memory for writing
$shm_id = shmop_open(SHM_IDENTIFIER, "w", 0644, MAX_MESSAGE*MESSAGE_SIZE);

if($shm_id !== false){
  #write the message into memory
  #first write "empty message" to clear the old message, then write new message
  $address = ($slot-1)*MESSAGE_SIZE;
  $shm_bytes_written = shmop_write($shm_id, EMPTY_MESSAGE, $address );
  $shm_bytes_written = shmop_write($shm_id, $message, $address );
  shmop_close($shm_id);
}

function print_help_message() {
  $msg  = "DCLed - state tracker client.\n";
  $msg .= "Usage:\t\t client.php n message\n";
  $msg .= "n:\t\t 1 to 8 - slot which the message is put in\n";
  $msg .= "message:\t a 128 character string to be displayed\n";
  echo $msg;
}