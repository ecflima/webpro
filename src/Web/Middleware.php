<?php

namespace Ecfl\Webpro\Web;

#[Attribute]
class Middleware {
	private $path;

    public function __construct($path) {
        $this->path = $path;
    }
}