<?php
/**
 * This file contains class::Running
 * @package Runalyze\Calculations
 */

use Runalyze\Configuration;

/**
 * Class: Running
 * @author Hannes Christiansen
 * @package Runalyze\Calculations
 */
class Running {
	/**
	 * Display a distance as km or m
	 * @param float $km       Distance [km]
	 * @param int $decimals   Decimals after the point, default: 1
	 * @param bool $track     Run on a tartan track?, default: false
	 */
	public static function Km($km, $decimals = -1, $track = false) {
		if ($km == 0)
			return '';

		if ($track)
			return self::KmFormatTrack($km).'m';

		return self::KmFormat($km, $decimals).'&nbsp;km';
	}

	/**
	 * Format distance
	 * @param double $km
	 * @param int $decimals [optional]
	 * @return string
	 */
	public static function KmFormat($km, $decimals = -1) {
		if ($decimals == -1)
			$decimals = Configuration::ActivityView()->decimals();

		return number_format($km, $decimals, ',', '.');
	}

	/**
	 * Format distance for track
	 * @param double $km
	 * @return string
	 */
	public static function KmFormatTrack($km) {
		return number_format($km*1000, 0, ',', '.');
	}

	/**
	 * Find the personal best for a given distance
	 * @uses self::Time
	 * @param float $dist       Distance [km]
	 * @param bool $return_time Return as integer, default: false
	 * @return mixed
	 */
	public static function PersonalBest($dist, $return_time = false) {
		$pb = DB::getInstance()->query('SELECT `s`, `distance` FROM `'.PREFIX.'training` WHERE `typeid`="'.Configuration::General()->competitionType().'" AND `distance`="'.$dist.'" ORDER BY `s` ASC LIMIT 1')->fetch();
		if ($return_time)
			return ($pb != '') ? $pb['s'] : 0;
		if ($pb != '')
			return Time::toString($pb['s']);
		return '<em>'.__('none').'</em>';
	}

	/**
	 * Get a string for displaying any pulse
	 * @param int $pulse
	 * @param int $time
	 * @return string
	 */
	public static function PulseString($pulse, $time = 0) {
		if ($pulse == 0)
			return '';

		$hf_max  = 0;
		$hf_rest = 0;

		if ($time != 0 && (time() - $time) > 365*DAY_IN_S) {
			$HFmax = DB::getInstance()->query('SELECT `time`,`pulse_max`,`pulse_rest` FROM `'.PREFIX.'user` ORDER BY ABS(`time`-'.$time.') ASC LIMIT 1')->fetch();
			if ($HFmax !== false && $HFmax['pulse_max'] != 0)
				$hf_max  = $HFmax['pulse_max'];
			if ($HFmax !== false && $HFmax['pulse_rest'] != 0)
				$hf_rest = $HFmax['pulse_rest'];
		}

		$bpm = self::PulseStringInBpm($pulse);
		$hf  = self::PulseStringInPercentHRmax($pulse, $hf_max);
		$hfr = self::PulseStringInPercentReserve($pulse, $hf_max, $hf_rest);

		if (Configuration::General()->heartRateUnit()->isHRmax())
			return Ajax::tooltip($hf, $bpm);

		if (Configuration::General()->heartRateUnit()->isHRreserve())
			return Ajax::tooltip($hfr, $bpm);
			
		return Ajax::tooltip($bpm, $hf);
	}

	/**
	 * Get string for pulse [bpm]
	 * @param int $pulse
	 * @return string
	 */
	public static function PulseStringInBpm($pulse) {
		return round($pulse).'&nbsp;bpm';
	}

	/**
	 * Get string for pulse in percent
	 * 
	 * HRmax or HRreserve is determined by configuration.
	 * 
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @param int $hf_rest [optional]
	 * @return string
	 */
	public static function PulseStringInPercent($pulse, $hf_max = 0, $hf_rest = 0) {
		return self::PulseInPercent($pulse, $hf_max, $hf_rest).'&nbsp;&#37;';
	}

	/**
	 * Get string for pulse [%HFmax]
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @return string
	 */
	public static function PulseStringInPercentHRmax($pulse, $hf_max = 0) {
		if ($hf_max == 0)
			$hf_max = HF_MAX;

		return self::PulseInPercentHRmax($pulse, $hf_max).'&nbsp;&#37;';
	}

	/**
	 * Get string for pulse [%HFres]
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @param int $hf_rest [optional]
	 * @return string
	 */
	public static function PulseStringInPercentReserve($pulse, $hf_max = 0, $hf_rest = 0) {
		return self::PulseInPercentReserve($pulse, $hf_max, $hf_rest).'&nbsp;&#37;';
	}

	/**
	 * Get pulse in percent
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @param int $hf_rest [optional]
	 * @return int
	 */
	public static function PulseInPercent($pulse, $hf_max = 0, $hf_rest = 0) {
		if (Configuration::General()->heartRateUnit()->isHRreserve())
			return self::PulseInPercentReserve($pulse, $hf_max, $hf_rest);

		return self::PulseInPercentHRmax($pulse, $hf_max);
	}

	/**
	 * Get pulse in percent of HRmax
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @return int
	 */
	public static function PulseInPercentHRmax($pulse, $hf_max = 0) {
		if ($hf_max == 0)
			$hf_max = HF_MAX;
		
		return round(100*$pulse / $hf_max);
	}

	/**
	 * Get pulse in percent of reserve
	 * @param int $pulse
	 * @param int $hf_max [optional]
	 * @param int $hf_rest [optional]
	 * @return int
	 */
	public static function PulseInPercentReserve($pulse, $hf_max = 0, $hf_rest = 0) {
		if ($hf_max == 0)
			$hf_max = HF_MAX;

		if ($hf_rest == 0)
			$hf_rest = HF_REST;

		return round(100*($pulse - $hf_rest) / ($hf_max - $hf_rest));
	}

	/**
	 * Creating a RGB-color for a given stress-value [0-100]
	 * @param int $stress   Stress-value [0-100]
	 */
	public static function Stresscolor($stress) {
		if ($stress > 100)
			$stress = 100;
		if ($stress < 0)
			$stress = 0;

		$gb = dechex(200 - 2*$stress);

		if ((200 - 2*$stress) < 16)
			$gb = '0'.$gb;

		return 'C8'.$gb.$gb;
	}

	/**
	 * Get colored string for a given value
	 * @param int $colorValue value to "color", between 0 and 100
	 * @param int $stringValue [optional] value to display, can be empty to use $colorValue
	 * @return string 
	 */
	static public function StresscoloredString($colorValue, $stringValue = false) {
		if ($stringValue === false)
			$stringValue = $colorValue;

		return '<span style="color:#'.self::Stresscolor($colorValue).';">'.$stringValue.'</span>';
	}

	/**
	 * Get the demanded pace if set in description (e.g. "... in 3:05 ...")
	 * @param string $description
	 * @return int
	 */
	public static function DescriptionToDemandedPace($description) {
		$array = explode("in ", $description);
		if (count($array) != 2)
			return 0;

		$array = explode(",", $array[1]);
		$array = explode(":", $array[0]);

		return sizeof($array) == 2 ? 60*$array[0]+$array[1] : 0;
	}

	/**
	 * Calculate factor concerning to basic endurance
	 * @param double $distance
	 * @return double
	 */
	static public function VDOTfactorOfBasicEndurance($distance) {
		$PrognosisDaniels = new RunningPrognosisDaniels;
		$PrognosisDaniels->setBasicEnduranceForAdjustment( BasicEndurance::getConst() );

		return $PrognosisDaniels->getAdjustmentFactor($distance);
	}
}