<?php
/*
 * DCLed - state tracker config
 *
 */

//max number of message slots - 8 is chosen because dcled has 8 preambles
define('MAX_MESSAGE', 8);
//max number of chars per message
define('MESSAGE_SIZE',128);
//key for the shared memory
define("SHM_IDENTIFIER", ftok('/tmp/shm_test', 'R'));
//empty message (ie. string with only \0)
define('EMPTY_MESSAGE', str_pad("\0", MESSAGE_SIZE, "\0"));
