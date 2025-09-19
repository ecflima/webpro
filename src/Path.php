<?php

namespace Ecfl\Webpro;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::TARGET_CLASS)]
class Path {
	private $path;

    public function __construct($path) {
        $this->path = $path;
    }
}