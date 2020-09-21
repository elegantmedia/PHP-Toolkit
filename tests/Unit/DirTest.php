<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Dir;
use ElegantMedia\PHPToolkit\Exceptions\FileSystem\DirectoryMissingException;
use PHPUnit\Framework\TestCase;

class DirTest extends TestCase
{
	private static $testDirName = "_test_dir";

	protected function tearDown()
	{
		if (is_dir(static::$testDirName)) {
			rmdir(static::$testDirName);
		}
		parent::tearDown();
	}

	/**
	 * @test
	 * @throws \EMedia\PHPHelpers\Exceptions\FIleSystem\DirectoryNotCreatedException
	 */
	public function testDirMakeDirectoryIfNotExistsSkipsExisting()
	{
		if (!is_dir(static::$testDirName)) {
			mkdir(static::$testDirName);
		}

		$success = Dir::makeDirectoryIfNotExists(static::$testDirName);
		$this->assertTrue($success);
	}

	/**
	 * @test
	 * @throws \EMedia\PHPHelpers\Exceptions\FIleSystem\DirectoryNotCreatedException
	 */
	public function testDirMakeDirectoryIfNotExistsCreatesNewDir()
	{

		if (is_dir(static::$testDirName)) {
			rmdir(static::$testDirName);
		}

		$success = Dir::makeDirectoryIfNotExists(static::$testDirName);

		$this->assertTrue($success);
		$this->assertTrue(is_dir(static::$testDirName));
	}

	/**
	 * @test
	 */
	public function testDirDeleteDirectoryThrowsIfMissing()
	{
		$this->expectException(DirectoryMissingException::class);
		Dir::deleteDirectory("missing");
	}

	/**
	 * @test
	 */
	public function testDirDeleteDirectoryDeletesFile()
	{
		$file = ".dir-manager-test.test.txt";
		file_put_contents($file, "foo");
		$this->assertFileExists($file);
		Dir::deleteDirectory($file);
		$this->assertFileNotExists($file);
	}

	public function testDirDeleteDirectoryDeletesDirectory()
	{
		$dir = ".dir-manager-test";
		mkdir($dir);
		mkdir("{$dir}/child");
		file_put_contents("{$dir}/test.txt", "foo");
		file_put_contents("{$dir}/child/test.txt", "foo");
		$this->assertFileExists($dir);
		Dir::deleteDirectory($dir);
		$this->assertFileNotExists($dir);
	}
}
