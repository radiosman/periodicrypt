#!/usr/bin/php
<?php
/**
 * @file
 * Command line wrapper for the periodicrypt library.
 */

namespace Periodicrypt_cli;
use \Commando\Command;

require_once('vendor/autoload.php');

/**
 * Main program execution.
 */
function main() {
  $cmd = new Command();
  $cmd->option()
    ->referToAs('method')
    ->require()
    ->describedAs("The operation to perform: 'encode' or 'decode'.")
    ->must(function($opt) {
      return in_array($opt, ["encode", "decode"]);
    });
  $cmd->option('n')
    ->aka('newlines')
    ->describedAs("Do not perform newline finangling.")
    ->boolean()
    ->default(TRUE);

  $arguments = $cmd->getArguments();
  $method = $arguments[0]->getValue();

  // Remove method from arguments.
  $arguments = array_slice($arguments, 1);
  if (empty($arguments)) {
    $stdin = fopen('php://stdin', 'r');
    // TODO this seems to be broken; the script hangs when it is piped to.
    $input = fread($stdin, 8192);
  }
  else {
    $input = array_reduce($arguments, function ($a, $v) {
      return $a . ' ' . $v->getValue();
    }, '');
  }

  $kajigger_newlines = $cmd->getOption('n')->getValue();
  if ($kajigger_newlines) {
    $input = rtrim($input, "\n");
  }

  $periodicrypt = new Periodicrypt($method, $input);

  $output = $periodicrypt->execute();

  if ($kajigger_newlines) {
    $output .= "\n";
  }

  print $output;
  exit(0);
}

main();
