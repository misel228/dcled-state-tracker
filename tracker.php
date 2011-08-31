#!/usr/bin/php -q
<?php
/*
 * DCLed - state tracker
 * repeatedly calls dcled to display what's in the memory
 * 
 */

require 'config.php';
declare(ticks = 1); #necessary to catch ctrl-c

// Catch Ctrl+C, kill and SIGTERM (Rollback)
pcntl_signal(SIGTERM, 'sigintShutdown');
pcntl_signal(SIGINT,  'sigintShutdown');

define('COMMAND_STRING', 'dcled -p %PRE% %MESSAGE% ');
$placeholders = array('%PRE%', '%MESSAGE%');


# Init: create a shared memory place and write an empty message into each of the MAX_MESSAGE slots

$shm_id = shmop_open(SHM_IDENTIFIER, "c", 0644, MAX_MESSAGE*MESSAGE_SIZE);
if($shm_id !== false){
  for($i=1;$i<=MAX_MESSAGE;$i++) {
    $shm_bytes_written = shmop_write($shm_id, EMPTY_MESSAGE, ($i-1)*MESSAGE_SIZE);
  }
  #close write access - is reopened read-only later 
  shmop_close($shm_id);
} else {
  die('Failed at creating shared memory area');
}



# Loop: reopen area read-only

$shm_id = shmop_open(SHM_IDENTIFIER, "a", 0644, MAX_MESSAGE*MESSAGE_SIZE);

if($shm_id !== false){

  while(true){
    for($i = 1; $i <= MAX_MESSAGE; $i++) {
//       var_dump('start: '.(($i-1)*MESSAGE_SIZE));
//       var_dump(MESSAGE_SIZE);
      $message = shmop_read($shm_id, ($i-1)*MESSAGE_SIZE, MESSAGE_SIZE);
      if($message != EMPTY_MESSAGE) {
//         var_dump($message);
//         var_dump(EMPTY_MESSAGE);
        $replacements = array($i, escapeshellarg($message));
        $command = str_replace($placeholders,$replacements, COMMAND_STRING);

        echo $command."\n";
        sleep(1);
      }
    }
  }
}


// remove memory area at end of program
function shutdown() {
  global $shm_id;
  shmop_delete($shm_id);
  shmop_close($shm_id);
  die('END OF PROGRAM'."\n");
}


/**
 * Function, that is executed, if script has been killed by
 * SIGINT: Ctrl+C
 * SIGTERM: kill
 *
 * @param int $signal
 *
 * shamelessly plugged from. http://www.phpdevblog.net/2009/05/cli-catching-ctrlc-kill-commands-and-fatal-errors.html
 */  
function sigintShutdown($signal) {
    if ($signal === SIGINT || $signal === SIGTERM) {
        shutdown();
    }
}  