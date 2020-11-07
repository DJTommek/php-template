<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Example dummy test.
 */
final class DummyTest extends TestCase
{
	/** @var string */
	private $dummyVariable;

	public function setUp(): void
	{
		$this->dummyVariable = 'setUp';
	}

	public function testDummyVariable(): void
	{
		$this->assertEquals('Coordinate is 49.123000', sprintf('Coordinate is %F', 49.123));
		$this->assertEquals('setUp', $this->dummyVariable);
	}

	public function testWithException(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Blah blah...');
		throw new \InvalidArgumentException('Blah blah...');
	}
}
