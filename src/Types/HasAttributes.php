<?php

namespace ElegantMedia\PHPToolkit\Types;

trait HasAttributes
{
	protected $attributes = [];

	public function __set($name, $value): void
	{
		$this->attributes[$name] = $value;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->attributes)) {
			return null;
		}

		return $this->attributes[$name];
	}

	public function __isset($name): bool
	{
		return isset($this->attributes[$name]);
	}

	public function __unset($name)
	{
		unset($this->attributes[$name]);
	}
}
