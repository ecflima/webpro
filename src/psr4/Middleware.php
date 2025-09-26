<?php

namespace Ecfl\Webpro;

#[Attribute]
class Middleware {
	private $path;

    public function __construct($path) {
        $this->path = $path;
    }
}