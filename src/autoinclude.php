<?php

function __autoload($class) {
    require_once __DIR__ . '/' . $class . '.php';
}