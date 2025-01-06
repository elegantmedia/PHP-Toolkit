<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{

	/**
	 *
	 */
	public function testTextTextToArray()
	{
		$strings = [
			"one\ntwo" => ["one", "two"],
			"one\r\ntwo" => ["one", "two"],
			"one\rtwo" => ["one", "two"],
			"one\r\ntwo\rthree\nfour" => ["one", "two", "three", "four"],
			" one , two ; three\n four " => ["one", "two", "three", "four"],
			"one, two,   three
four
five" => ["one", "two", "three", "four", "five"],
			"onetwo" => ["onetwo"],
			"" => [],
			" " => [],
			"," => []
		];

		foreach ($strings as $string => $result) {
			$this->assertEquals(Text::textToArray($string), $result);
		}
	}

	public function testTextreverseSnake()
	{
		$strings = [
			'foo' => 'foo',
			'foo_bar' => 'foo bar',
			'Bar_Baz' => 'Bar Baz',
			'_quex' => ' quex',
			0 => '0',
			null => ''
		];

		foreach ($strings as $string => $result) {
			$this->assertEquals(Text::reverseSnake($string), $result);
		}
	}

	public function testTextrandomUnambiguous()
	{
		$lengths = [0, 10, 16];

		foreach ($lengths as $length) {
			$this->assertEquals(strlen(Text::randomUnambiguous($length)), $length);
		}
	}
}
