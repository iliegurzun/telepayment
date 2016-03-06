<?php

/**
 * Created by PhpStorm.
 * User: Ilie
 * Date: 5/3/2016
 * Time: 1:52 PM
 */
namespace AppBundle\Tests\Services;

use AppBundle\Services\PaymentDateCalculator;
use AppBundle\Tests\AbstractTest;

/**
 * Class PaymentDateCalculatorTest
 * @package AppBundle\Tests\Services
 */
class PaymentDateCalculatorTest extends AbstractTest
{
    /**
     * Test for calculatePaymentDates method
     */
    public function testCalculatePaymentDates()
    {
        $service = $this->mockPaymentCalculator();
        $dates = $service->calculatePaymentDates();
        $this->assertEquals(12, count($dates));
        $this->assertEquals('February', $dates[0]['month']);
    }

    /**
     * Test for execute() method
     */
    public function testExecute()
    {
        // remove testing file if already exists
        try {
            unlink('foobar.csv');
        } catch (\Exception $ex) {}
        $service = $this->mockPaymentCalculator();
        $service->execute();
        $this->assertFileExists('foobar.csv');
        unlink('foobar.csv');
    }

    /**
     * Mock PaymentDateCalculator
     *
     * @return PaymentDateCalculator
     */
    protected function mockPaymentCalculator()
    {
        $service = $this
            ->getMockBuilder(PaymentDateCalculator::class)
            ->setMethods(
                array(
                    'getCurrentMonth',
                    'getFilename'
                )
            )
            ->getMock();
        $service->expects($this->any())
            ->method('getCurrentMonth')
            ->will($this->returnValue(1));
        $service->expects($this->any())
            ->method('getFilename')
            ->will($this->returnValue('foobar.csv'));

        return $service;
    }
}