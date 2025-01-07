<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Conversion;
use PHPUnit\Framework\Attributes\Test;

class ConversionTest extends \PHPUnit\Framework\TestCase
{
	#[Test]
	public function testConversionBytesToHumansConvertsToDecimals()
	{
		$bytes = [
			200 => '200 B',
			800000 => '781.25 KB',
			0 => '0 B',
			10000000 => '9.54 MB',
			9999999999 => '9.31 GB',
			8888888888888 => '8.08 TB'
		];

		foreach ($bytes as $byte => $result) {
			$this->assertEquals(Conversion::bytesToHumans($byte), $result);
		}
	}

	#[Test]
	public function testConversionBytesToHumansConvertsToWhole()
	{
		$bytes = [
			200 => '200 B',
			0 => '0 B',
			10000000 => '10 MB',
			9999999999 => '9 GB',
			8888888888888 => '8 TB'
		];

		foreach ($bytes as $byte => $result) {
			$this->assertEquals(Conversion::bytesToHumans($byte, 0), $result);
		}
	}

	#[Test]
	public function testConversionConvertToIntegerConvertsToWholeNumber()
	{
		$strings = [
			'1' => 1,
			'2.8' => 3,
			0 => false,
			null => false,
			'foo' => 0
		];

		foreach ($strings as $string => $result) {
			$this->assertEquals($result, Conversion::stringToInt($string));
		}
	}

	#[Test]
	public function testConversionConvertToFloatConvertsToFloat()
	{
		$strings = [
			'1' => 1,
			'2.8' => 2.8,
			'1.245' => 1.245,
			'1,000.50' => 1000.5,
			null => false,
			'foo' => 0
		];

		foreach ($strings as $string => $result) {
			$this->assertEquals($result, Conversion::stringToFloat($string));
		}
	}
}
