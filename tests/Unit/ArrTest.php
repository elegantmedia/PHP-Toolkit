<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Arr;

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
	
	/**
	 * @test
	 */
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

	/**
	 * @test
	 */
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

	/**
	 * @test
	 */
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
}
