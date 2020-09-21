<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit\Helpers;

use InvalidArgumentException;

class ValidationHelpersTest extends \PHPUnit\Framework\TestCase
{

	/**
	 *
	 */
	public function testValidationHelpersValidateAllPresentIfNull()
	{
		$this->expectException(InvalidArgumentException::class);
		validate_all_present("foo", null);
	}

	/**
	 *
	 */
	public function testValidationHelpersValidateAllPresentIfEmpty(): void
	{
		$this->expectException(InvalidArgumentException::class);
		validate_all_present("foo", []);
	}

	/**
	 *
	 */
	public function testValidationHelpersValidateAllPresentIfBlank()
	{
		$this->expectException(InvalidArgumentException::class);
		validate_all_present("foo", "");
	}

	/**
	 *
	 */
	public function testValidationHelpersValidateAllPresentIFFalse()
	{
		$this->expectException(InvalidArgumentException::class);
		validate_all_present("foo", "", false);
	}

	/**
	 *
	 */
	public function testValidationHelpersValidateAllPresent()
	{
		validate_all_present("foo", 1, true, ["foo" => "bar"]);
		$this->assertTrue(true);
	}
}
