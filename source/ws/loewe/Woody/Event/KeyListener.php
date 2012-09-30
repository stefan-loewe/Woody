<?php

namespace ws\loewe\Woody\Event;

interface KeyListener {
  /**
   * This method is called, when the key-pressed event occurs.
   */
  function keyPressed(KeyEvent $event);

  /**
   * This method is called, when the key-pressed event occurs.
   */
  function keyReleased(KeyEvent $event);
}