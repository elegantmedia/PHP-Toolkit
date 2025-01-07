<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Arr;
use PHPUnit\Framework\Attributes\Test;

class ArrTest extends \PHPUnit\Framework\TestCase
{

	/**
	 *
	 *
	 *
	 */
	public function testArrIntersectRecursiveSimpleSubsetMatch1(): void
	{
		$input = [
			'payload' => [
				[
					'item' => 'item 1',
					'desc' => 'desc',
				]
			]
		];

		$subset = [
			'payload' => [
				[
					'item' => 'item 1',
					'desc' => 'desc',
				]
			]
		];

		$response = Arr::intersectRecursive($input, $subset);

		self::assertTrue($response == $subset);
	}

	public function testArrIntersectRecursiveSimpleSubsetMatch2()
	{
		$input = [
			'payload' => [
				[
					'item' => 'item 1',
					'desc' => 'desc',
				],
				[
					'item' => 'item 2',
					'desc' => 'desc',
				],
				[
					'item' => 'item 3',
					'desc' => 'desc',
				]
			]
		];

		$subset = [
			'payload' => [
				[
					'item' => 'item 2',
					'desc' => 'desc',
				],
			]
		];

		$response = Arr::intersectRecursive($input, $subset);

		self::assertTrue($response == $subset);
	}

	public function testArrIntersectRecursiveSimpleSubsetMatch3()
	{
		$input = [
			'payload' => [
				[
					'item' => 'item 1',
					'desc' => 'desc',
				],
				[
					'item' => 'item 2',
					'desc' => 'desc',
				],
				[
					'item' => 'item 3',
					'desc' => 'desc',
				]
			]
		];

		$subset = [
			'payload' => [
				[
					'item' => 'item 2',
				],
			]
		];

		$response = Arr::intersectRecursive($input, $subset);
		self::assertTrue($response == $subset);
	}

	public function testArrIntersectRecursiveSimpleSubsetMatch4()
	{
		$input = [
			'payload' => [
				[
					'item' => 'item 1',
					'desc' => 'desc',
					'is_read' => true,
				],
				[
					'item' => 'SENT_SINGLE_DEVICE_SEED_NOTIFICATION_2',
					'is_read' => false,
					'badge_count' => '',
				],
				[
					'item' => 'item 3',
					'desc' => 'desc',
				]
			]
		];

		$subset = [
			'payload' => [
				[
					'item' => 'SENT_SINGLE_DEVICE_SEED_NOTIFICATION_2',
					'is_read' => true,
				],
			]
		];

		$response = Arr::intersectRecursive($input, $subset);

		self::assertFalse($response == $subset);
	}

	public function testArrIsAssocDetectsAssocArray()
	{
		$array = [
			'one' => 1,
			'two' => 2,
		];
		$result = Arr::isAssoc($array);

		self::assertTrue($result);

		$array = [
			1, 2, 4
		];
		$result = Arr::isAssoc($array);

		self::assertFalse($result);
	}


	public function testArrIsAssocDetectsArrayNonArrays()
	{
		self::assertFalse(Arr::isAssoc('test'));
	}

	#[Test]
	public function testArrIntersectRecursiveChecksSingleKey()
	{
		$arr = [
			[
				"name" => "john",
			]
		];

		$subset = [
			[
				"name" => "john",
			],
			[
				"name" => "jane"
			]
		];

		$result = Arr::intersectRecursive($arr, $subset);

		$this->assertEquals($arr, $result);
	}

	#[Test]
	public function testArrIntersectRecursiveChecksMultipleKeys()
	{
		$arr = [
			[
				"name" => "john",
				"age" => 10
			]
		];

		$subset = [
			[
				"name" => "john",
				"age" => 10
			],
			[
				"name" => "john",
				"age" => 45
			],
			[
				"name" => "jane"
			]
		];

		$result = Arr::intersectRecursive($arr, $subset);

		$this->assertEquals($arr, $result);
	}

	#[Test]
	public function testArrIntersectRecursiveChecksNestedKeys()
	{
		$arr = [
			[
				"name" => "john",
				"hobbies" => [
					["name" => "Reading"],
					["name" => "Watching Movies"]
				]
			]
		];

		$subset = [
			[
				"name" => "john",
				"hobbies" => [
					["name" => "Reading"],
					["name" => "Watching Movies"]
				]
			],
			[
				"name" => "john",
				"hobbies" => [
					["name" => "Reading"],
				]
			],
			[
				"name" => "jane"
			]
		];

		$result = Arr::intersectRecursive($arr, $subset);
		$arr[0] = Arr::removeChildArrays($arr[0]);
		$this->assertEquals($arr, $result);
	}

	public function testArrImplodeIgnoreEmpty()
	{
		$this->assertEquals(
			'one,two,three',
			Arr::implodeIgnoreEmpty(',', ['one', '', 'two', '', 'three'])
		);
	}

	public function testArraySwapKey()
	{
		$oldKey = "old_key";
		$valueKey = "name";
		$value = 'the_value';
		$newKey = "new_key";

		$arr = [
			[
				$oldKey => [
					[
						$oldKey => [
							[
								$valueKey => $value
							]
						],
					]
				],
			]
		];

		$newArr = Arr::swapKey($arr, $oldKey, $newKey);
		$this->assertEquals($value, $newArr[0][$newKey][0][$oldKey][0][$valueKey]);
	}

	public function testSwapKeysFnAppliesFunction()
	{
		$array = [
			'my-life' => 1,
			'my new' => 2,
			'myCar' => 3,
		];

		self::assertFalse(array_key_exists('MY-LIFE', $array));

		Arr::swapKeysFn($array, 'strtoupper');

		self::assertTrue(array_key_exists('MY-LIFE', $array));
	}

	public function testSwapKeysFnNonArraysAreIgnored()
	{
		$str = 'string';
		Arr::swapKeysFn($str, 'strtoupper');

		self::assertEquals($str, 'string');
	}

	public function testArraySwapKeyRecursive()
	{
		$oldKey = "old_key";
		$valueKey = "name";
		$value = 'the_value';
		$newKey = "new_key";

		$arr = [
			[
				$oldKey => [
					[
						$oldKey => [
							[
								$valueKey => $value
							]
						],
					]
				],
			]
		];

		$newArr = Arr::swapKey($arr, $oldKey, $newKey, true);
		$this->assertEquals($value, $newArr[0][$newKey][0][$newKey][0][$valueKey]);
	}

	public function testKeyByConvertsArray()
	{
		$arr = [
			['name' => 'john', 'age' => 45],
			['name' => 'jane', 'age' => 32],
		];

		$key = 'name';

		$result = Arr::keyBy($arr, $key);

		foreach ($arr as $index => $original) {
			$newKey = $arr[$index][$key];
			$this->assertEquals($result[$newKey][0]["age"], $arr[$index]["age"]);
		}
	}
}
