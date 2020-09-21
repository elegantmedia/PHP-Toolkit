<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
	public function testPathwithEndingSlash()
	{
		$this->assertEquals('/my-test-path/', Path::withEndingSlash('/my-test-path'));

		$this->assertEquals('/my-test-path/', Path::withEndingSlash('/my-test-path///'));

		$this->assertEquals('/my-test-path/', Path::withEndingSlash('/my-test-path/'));

		$this->assertEquals('/my-test-path/', Path::withEndingSlash('/my-test-path//'));
	}

	public function testPathwithoutEndingSlash()
	{
		$this->assertEquals('/my-test-path', Path::withoutEndingSlash('/my-test-path'));

		$this->assertEquals('/my-test-path', Path::withoutEndingSlash('/my-test-path///'));

		$this->assertEquals('/my-test-path', Path::withoutEndingSlash('/my-test-path/'));

		$this->assertEquals('/my-test-path', Path::withoutEndingSlash('/my-test-path//'));
	}

	public function testPathwithoutStartingSlash()
	{
		$this->assertEquals('my-test-path/new', Path::withoutStartingSlash('/my-test-path/new'));

		$this->assertEquals('my-test-path/new///', Path::withoutStartingSlash('//my-test-path/new///'));

		$this->assertEquals('my-test-path/new/', Path::withoutStartingSlash('my-test-path/new/'));
	}

	public function testPathcanonical()
	{
		$values = [
			'/folder1/folder2/./../folder3' => '/folder1/folder3',
			'/folder1/folder2/../../folder3' => '/folder3',
			'/folder1/folder2/../../../folder3' => 'folder3',
		];

		foreach ($values as $input => $expected) {
			$this->assertEquals($expected, Path::canonical($input));
		}
	}
}
