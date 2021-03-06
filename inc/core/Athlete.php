<?php
/**
 * This file contains class::Athlete
 * @package Runalyze
 */

namespace Runalyze;

use \Runalyze\Parameter\Application\Gender;

/**
 * Athlete
 * 
 * @author Hannes Christiansen
 * @package Runalyze
 */
class Athlete {
	/**
	 * Gender
	 * @var \Runalyze\Parameter\Application\Gender
	 */
	protected $Gender;

	/**
	 * Maximal heart rate [bpm]
	 * @var int
	 */
	protected $maximalHR = null;

	/**
	 * Resting heart rate [bpm]
	 * @var int
	 */
	protected $restingHR = null;

	/**
	 * Weight [kg]
	 * @var float
	 */
	protected $weight = null;

	/**
	 * Age [years]
	 * @var int
	 */
	protected $age = null;

	/**
	 * Create athlete
	 * @param \Gender $Gender [optional]
	 * @param int $maximalHR [optional]
	 * @param int $restingHR [optional]
	 * @param flot $weight [optional]
	 * @param int $age [optional]
	 */
	public function __construct(
		Gender $Gender = null,
		$maximalHR = null,
		$restingHR = null,
		$weight = null,
		$age = null
	) {
		$this->Gender = $Gender ?: new Gender();
		$this->maximalHR = $maximalHR;
		$this->restingHR = $restingHR;
		$this->weight = $weight;
		$this->age = $age;
	}

	/**
	 * Gender
	 * @return \Runalyze\Parameter\Application\Gender
	 */
	public function gender() {
		return $this->Gender;
	}

	/**
	 * Maximal heart rate
	 * @return int
	 */
	public function maximalHR() {
		return $this->maximalHR;
	}

	/**
	 * Resting heart rate
	 * @return int
	 */
	public function restingHR() {
		return $this->restingHR;
	}

	/**
	 * Weight
	 * @return int
	 */
	public function weight() {
		return $this->weight;
	}

	/**
	 * Age
	 * @return int
	 */
	public function age() {
		return $this->age;
	}

	/**
	 * Knows gender
	 * @return bool
	 */
	public function knowsGender() {
		return $this->Gender->hasGender();
	}

	/**
	 * Knows maximal HR
	 * @return bool
	 */
	public function knowsMaximalHeartRate() {
		return (NULL !== $this->maximalHR);
	}

	/**
	 * Knows resting HR
	 * @return bool
	 */
	public function knowsRestingHeartRate() {
		return (NULL !== $this->restingHR);
	}

	/**
	 * Knows weight
	 * @return bool
	 */
	public function knowsWeight() {
		return (NULL !== $this->weight);
	}

	/**
	 * Knows age
	 * @return bool
	 */
	public function knowsAge() {
		return (NULL !== $this->age);
	}
}