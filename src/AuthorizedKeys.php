<?php
namespace pagemachine\AuthorizedKeys;

/*
 * This file is part of the pagemachine Authorized Keys project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Manages the authorized_keys file
 */
class AuthorizedKeys {

  /**
   * Lines of the file
   *
   * @var array
   */
  protected $lines = [];

  /**
   * @param string $content content of the authorized_keys file
   */
  public function __construct($content = null) {

    if (!empty($content)) {

      $this->lines = $this->parse($content);
    }
  }

  /**
   * Creates a new instance from a file
   *
   * @param string $file path of authorized_keys file
   * @return AuthorizedKeys
   */
  public static function fromFile($file) {

    $content = file_get_contents($file);

    return new static($content);
  }

  /**
   * Add a public key to the file
   *
   * @param PublicKey $key a public key
   */
  public function addKey(PublicKey $key) {

    $this->lines[] = $key;
  }

  /**
   * Returns the file content as string
   *
   * @return string
   */
  public function __toString() {

    return implode("\n", $this->lines);
  }

  /**
   * Parses content of a authorized_keys file
   *
   * @param string $content content of the authorized_keys file
   * @return array
   */
  protected function parse($content) {

    $lines = explode("\n", $content);
    $lines = array_map('trim', $lines);

    foreach ($lines as $i => $line) {

      if (!empty($line) || $line[0] !== '#') {

        $lines[$i] = new PublicKey($line);
      }
    }

    return $lines;
  }
}
