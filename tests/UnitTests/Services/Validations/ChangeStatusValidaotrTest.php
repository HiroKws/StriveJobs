<?php

use Mockery as m;
use StriveJobs\Services\Validations\ChangeStatusValidator;
use StriveJobs\TestCase;

class ChangeStatusValidatorTest extends TestCase
{

    public function testFireWithoutCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( 'Please specify targets by using one option. (--id and --notEqual can be used multiply.)',
                             $validator->validate( array( ) ) );
    }

    public function testFireWithOneStatusCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '',
                             $validator->validate( array( 'status' => array( 'dummy1' ) ) ) );
    }

    public function testFireWithManyStatusCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '',
                             $validator->validate( array( 'status' => array( 'dummy1', 'dummy2' ) ) ) );
    }

    public function testFireWithOneIdCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );
        $striveJobsMock->shouldReceive( 'isExistJobs' )
            ->once()
            ->with( array( 15 ) )
            ->andReturn( true );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '', $validator->validate( array( 'id' => array( '15' ) ) ) );
    }

    public function testFireWithManyIdCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );
        $striveJobsMock->shouldReceive( 'isExistJobs' )
            ->once()
            ->with( array( 15, 20 ) )
            ->andReturn( true );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '',
                             $validator->validate( array( 'id' => array( '15', '20' ) ) ) );
    }

    public function testFireWithNoExistedId()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );
        $striveJobsMock->shouldReceive( 'isExistJobs' )
            ->once()
            ->with( array( 15 ) )
            ->andReturn( false );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( 'ID not found in registered jobs.',
                             $validator->validate( array( 'id' => array( '15' ) ) ) );
    }

    public function testFireWithOneNotIdCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '', $validator->validate( array( 'notId' => array( '31' ) ) ) );
    }

    public function testFireWithManyNotIdCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '',
                             $validator->validate( array( 'notId' => array( '31', '96' ) ) ) );
    }

    public function testFireWithOneLessThanCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '', $validator->validate( array( 'lessThan' => '11' ) ) );
    }

    public function testFireWithOneLessThanEqualCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '', $validator->validate( array( 'lessThanEqual' => '15' ) ) );
    }

    public function testFireWithOneGreaterThanCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '', $validator->validate( array( 'greaterThan' => '33' ) ) );
    }

    public function testFireWithOneGreaterThanEqualCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( '',
                             $validator->validate( array( 'greaterThanEqual' => '53' ) ) );
    }

    public function testFireWithManyCondition()
    {
        $striveJobsMock = m::mock( 'StriveJobs\StriveJobs' );

        $validator = new ChangeStatusValidator( $striveJobsMock );

        $this->assertEquals( 'Please specify targets by using one option. (--id, --notEqual and --status can be used multiply.)',
                             $validator->validate( array(
                'id'               => array( '30' ),
                'greaterThanEqual' => '53'
                )
            )
        );
    }

}