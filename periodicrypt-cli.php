#!/usr/bin/php
<?php
/**
 * @file
 * Command line wrapper for the periodicrypt library.
 */

/**
 * Loads the libperiodicrypt library.
 * Looks for environment variable LIBPERIODICRYPTDIR or in the current directory.
 * Exits with error if no file found.
 */
function load_periodicrypt() {
  $dir = isset($_ENV['LIBPERIODICRYPTDIR']) ? $_ENV['LIBPERIODICRYPTDIR'] : '.';
  $file = $dir . '/libperiodicrypt.php';
  if (file_exists($file)) {
    require($file);
  }
  else {
    print "libperiodicrypt not found. For more information see https://github.com/radiosman/periodicrypt.\n";
    exit(1);
  }
}

/**
 * Prints a usage message and exits.
 */
function usage() {
  print 'Usage: ' . $_SERVER['argv'][0] . ' encode|decode "encode this" message' . "\n";
  exit(1);
}

/**
 * Ensure that a minimum number of arguments are given.
 * If verification fails, call usage().
 */
function verify_args() {
  $num_args = count($_SERVER['argv']);
  $valid_commands = array('encode', 'decode');
  if ($num_args < 2 || !in_array($_SERVER['argv'][1], $valid_commands)) {
    usage();
  }
}

/**
 * Build message to (en|de)code.
 */
function getMessage() {
  $args = array_slice($_SERVER['argv'], 2);

  if (!empty($args)) {
    return join(' ', $args);
  }
  else {
    $stdin = fopen('php://stdin', 'r');
    return fread($stdin, 8192);
  }
}

/**
 * Main program execution.
 */
function main() {
  load_periodicrypt();
  verify_args();
  $message = getMessage();
  $coding_function = false;
  $coded_message = false;

  switch ($_SERVER['argv'][1]) {
    case 'encode':
      $coding_function = 'HashIt';
      break;
    case 'decode':
      $coding_function = 'UnhashIt';
      break;
    default:
      usage();
  }

  $coded_message = $coding_function($message);

  print $coded_message;

  exit(0);

}

main();
?>
