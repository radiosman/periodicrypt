<?php
/**
 * @file
 * Periodicrypt class.
 */

namespace Periodicrypt_cli;

/**
 * Class Periodicrypt
 *
 * @package Periodicrypt_cli
 */
class Periodicrypt {

  /**
   * @var string
   *   The operation mode: 'encrypt' or 'decrypt'.
   */
  protected $mode;

  /**
   * @var array
   *   Options from the command line.
   */
  protected $options = [];

  /**
   * @var string
   *   The input message.
   */
  protected $message;


  /**
   * @var string
   *   The output message.
   */
  protected $output;

  /**
   * Cli constructor.
   *
   * @param string $method
   *   'encode' or 'decode'.
   * @param string $string
   *   The value to encode or decode.
   * @param array $options
   *   An optional array of options.
   */
  function __construct(string $method, string $string, array $options = array()) {
    if (!in_array($method, ['encode', 'decode'])) {
      throw new \InvalidArgumentException("Method must be 'encode' or 'decode'.");
    }
    $this->mode = $method;
    $this->message = $string;

    $this->loadLibrary();
  }

  /**
   * Execute the encrypting or decrypting operation.
   *
   * @return string
   *   The output message.
   */
  public function execute() {
    switch ($this->mode) {
      case 'encode':
        $coding_function = 'HashIt';
        break;
      case 'decode':
        $coding_function = 'UnhashIt';
        break;
      default:
        throw new \InvalidArgumentException("The mode parameter must be 'encode' or 'decode'.");
    }

    $this->output = $coding_function($this->message);

    return $this->output;
  }

  /**
   * Loads the libperiodicrypt library.
   * Looks for environment variable LIBPERIODICRYPTDIR or in the current directory.
   *
   * @throws \Exception
   */
  protected function loadLibrary() {
    $dir = isset($_ENV['LIBPERIODICRYPTDIR']) ? $_ENV['LIBPERIODICRYPTDIR'] : '.';
    $file = $dir . '/libperiodicrypt.php';
    if (file_exists($file)) {
      require($file);
    }
    else {
      throw new \Exception("libperiodicrypt not found. For more information see https://github.com/radiosman/periodicrypt.\n");
    }
  }

}
