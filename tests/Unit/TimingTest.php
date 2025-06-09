<?php
namespace ElegantMedia\PHPToolkit\Tests\Unit;

use ElegantMedia\PHPToolkit\Timing;
use PHPUnit\Framework\TestCase;

class TimingTest extends TestCase
{


	/**
	 *
	 */
	public function testTimingMicroTimestampUnqiue(): void
	{
		$timestamp1 = Timing::microTimestamp();
		usleep(1); // Sleep for 1 microsecond to ensure different timestamps
		$timestamp2 = Timing::microTimestamp();
		$this->assertNotEquals($timestamp1, $timestamp2);
	}

	/**
	 *
	 */
	public function testTimingMicroTimestampIsTodayDate(): void
	{
		$date = explode('.', Timing::microTimestamp())[0];
		$parsed = date_parse_from_format("Y_m_d_His", $date);

		$now = date_parse(date('Y-m-d'));

		$this->assertEquals($now["year"], $parsed["year"]);
		$this->assertEquals($now["month"], $parsed["month"]);
		$this->assertEquals($now["day"], $parsed["day"]);
	}
}
