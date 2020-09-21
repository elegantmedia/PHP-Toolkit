<?php


namespace ElegantMedia\PHPToolkit;

class Reflector
{

	/**
	 *
	 * Get the inherited class' directory path
	 *
	 * @param $self
	 * @param null $pathSuffix
	 * @return string
	 * @throws \ReflectionException
	 */
	public static function classPath($self, $pathSuffix = null): string
	{
		$reflector = new \ReflectionClass(get_class($self));

		$path = dirname($reflector->getFileName());

		if ($pathSuffix) {
			$path .= DIRECTORY_SEPARATOR.ltrim($pathSuffix, DIRECTORY_SEPARATOR);
		}

		return Path::canonical($path);
	}
}
