<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Loader as LoaderAlias;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
class LoaderTest extends TestCase
{
	private static $dir = "_loader_test";
	private static $subdir = "_loader_test/sub";

	protected function setUp(): void
	{
		parent::setUp(); // TODO: Change the autogenerated stub
		mkdir(static::$dir);
		mkdir(static::$subdir, 0777, true);
	}

	protected function tearDown(): void
	{
		system('rm -rf -- ' . escapeshellarg(static::$dir));
		parent::tearDown(); // TODO: Change the autogenerated stub
	}

	#[Test]
	public function testLoaderIncludesClass()
	{
		$classes = [
			static::$dir => 'ClassOne',
			static::$dir => 'ClassTwo',
			static::$subdir => 'ChildClass'
		];

		foreach ($classes as $directory => $class) {
			$this->assertFalse(class_exists($class));
		}

		foreach ($classes as $directory => $class) {
			$this->generateClassFile($directory, $class);
		}

		LoaderAlias::includeAllFilesFromDir(static::$dir);

		foreach ($classes as $directory => $class) {
			if ($directory === static::$dir) {
				$this->assertTrue(class_exists($class));
			}

			if ($directory === static::$subdir) {
				$this->assertFalse(class_exists($class));
			}
		}
	}

	#[Test]
	public function testLoaderIncludesClassRecursive()
	{
		$classes = [
			static::$dir => 'ParentClassOne',
			static::$dir => 'ParentClassTwo',
			static::$subdir => 'SubDirectoryClass'
		];

		foreach ($classes as $directory => $class) {
			$this->assertFalse(class_exists($class));
		}

		foreach ($classes as $directory => $class) {
			$this->generateClassFile($directory, $class);
		}

		LoaderAlias::includeAllFilesFromDirRecursive(static::$dir);

		foreach ($classes as $directory => $class) {
			$this->assertTrue(class_exists($class));
		}
	}

	private function generateClassFile($dir, $name)
	{
		$content = "<?php \r class {$name} \r { \r \r }";
		file_put_contents($dir . "/{$name}.php", $content);
	}
}
