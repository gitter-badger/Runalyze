<?php

namespace Runalyze\Calculation\Performance;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-10-26 at 11:12:12.
 */
class ModelQueryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var \PDOforRunalyze
	 */
	protected $DB;

	protected function setUp() {
		$this->DB = \DB::getInstance();
		$this->DB->exec('TRUNCATE TABLE `runalyze_training`');

		$Date = new \DateTime('today');
		$this->DB->insert('training', array('time', 'trimp', 'sportid'), array($Date->getTimestamp(), 100, 1));

		$Date->modify('-1 day');
		$this->DB->insert('training', array('time', 'trimp', 'sportid'), array($Date->getTimestamp(), 30, 1));
		$this->DB->insert('training', array('time', 'trimp', 'sportid'), array($Date->getTimestamp(), 20, 2));

		$Date->modify('-2 days');
		$this->DB->insert('training', array('time', 'trimp', 'sportid'), array($Date->getTimestamp(), 70, 2));

		$Date->modify('-7 days');
		$this->DB->insert('training', array('time', 'trimp', 'sportid'), array($Date->getTimestamp(), 150, 1));
	}

	protected function tearDown() {
		$this->DB->exec('TRUNCATE TABLE `runalyze_training`');
	}

	public function testSimpleQuery() {
		$Query = new ModelQuery();
		$Query->execute($this->DB);

		$this->assertEquals( array(
			-10 => 150,
			-3 => 70,
			-1 => 50,
			0 => 100
		), $Query->data());
	}

	public function testTimeRange() {
		$WeekAgo = new \DateTime('-1 week');
		$Yesterday = new \DateTime('yesterday');

		$Query = new ModelQuery();
		$Query->setRange($WeekAgo->getTimestamp(), $Yesterday->getTimestamp());
		$Query->execute($this->DB);

		$this->assertEquals( array(
			-3 => 70,
			-1 => 50
		), $Query->data());
	}

	public function testSportid() {
		$Query = new ModelQuery();
		$Query->setSportid(1);
		$Query->execute($this->DB);

		$this->assertEquals( array(
			-10 => 150,
			-1 => 30,
			0 => 100
		), $Query->data());

		$Query->setSportid(2);
		$Query->execute($this->DB);

		$this->assertEquals( array(
			-3 => 70,
			-1 => 20
		), $Query->data());
	}
}