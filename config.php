<?php
/*
 * DCLed - state tracker config
 *
 * see: https://github.com/misel228/dcled-state-tracker
 */

//max number of message slots - 8 is chosen because dcled has 8 preambles
define('MAX_MESSAGE', 8);
//max number of chars per message
define('MESSAGE_SIZE',128);
//timestamp for each slot after which the message will be ignored
//stored in the first 10 digits of a message
define('TIME_STAMP_SIZE',10);
define('MAX_TIME', 86400); # one day
//key for the shared memory
define("SHM_IDENTIFIER", 0x2222);
//empty message (ie. string with only \0)
define('EMPTY_MESSAGE', str_pad("\0", MESSAGE_SIZE, "\0"));
