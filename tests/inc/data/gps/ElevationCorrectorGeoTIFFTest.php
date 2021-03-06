<?php
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-01-27 at 21:01:47.
 */
class ElevationCorrectorGeoTIFFTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var ElevationCorrectorGeoTIFF
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {}

	/**
	 * @covers ElevationCorrectorGeoTIFF::canHandleData
	 * @covers ElevationCorrectorGeoTIFF::correctElevation
	 */
	public function testImpossibleData() {
		$FirstCorrector = new ElevationCorrectorGeoTIFF(
			array(90),
			array(0)
		);

		$this->assertFalse( $FirstCorrector->canHandleData() );
	}

	/**
	 * @covers ElevationCorrectorGeoTIFF::canHandleData
	 * @covers ElevationCorrectorGeoTIFF::correctElevation
	 */
	public function testPossibleData() {
		if (file_exists(FRONTEND_PATH.'data/gps/srtm/srtm_38_03.tif')) {
			// Assuming that srtm_38_03.tif is present
			// Coordinates of Kaiserslautern
			$Corrector = new ElevationCorrectorGeoTIFF(
				array(49.444722), 
				array(7.768889)
			);

			$this->assertTrue( $Corrector->canHandleData() );

			$Corrector->correctElevation();

			$this->assertEquals( array(238), $Corrector->getCorrectedElevation() );
		}
	}

	/**
	 * @covers ElevationCorrectorGeoTIFF::canHandleData
	 * @covers ElevationCorrectorGeoTIFF::correctElevation
	 */
	public function testPossiblePath() {
		if (file_exists(FRONTEND_PATH.'data/gps/srtm/srtm_38_03.tif')) {
			// Assuming that srtm_38_03.tif is present
			// Coordinates of Kaiserslautern
			$Corrector = new ElevationCorrectorGeoTIFF(
				array(49.440, 49.441, 49.442, 49.443, 49.444, 49.445, 49.446, 49.447, 49.448, 49.449, 49.450), 
				array(7.760, 7.761, 7.762, 7.763, 7.764, 7.765, 7.766, 7.767, 7.768, 7.769, 7.770)
			);

			$this->assertTrue( $Corrector->canHandleData() );

			$Corrector->correctElevation();

			//$this->assertEquals( array(240, 238, 240, 238, 238, 237, 236, 237, 240, 248, 259), $Corrector->getCorrectedElevation() );
			// Because of the activated smoothing:
			$this->assertEquals( array(240.0, 240.0, 240.0, 240.0, 240.0, 241.0, 241.0, 241.0, 241.0, 241.0, 254.0), $Corrector->getCorrectedElevation() );
		}
	}

}
