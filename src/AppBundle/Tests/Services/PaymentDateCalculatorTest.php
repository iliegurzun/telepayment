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
     * Mock PaymentDateCalculator
     *
     * @return PaymentDateCalculator
     */
    protected function mockPaymentCalculator()
    {
        $service = $this
            ->getMockBuilder(PaymentDateCalculator::class)
            ->setMethods(array('getCurrentMonth'))
            ->getMock();
        $service->expects($this->any())
            ->method('getCurrentMonth')
            ->will($this->returnValue(1));

        return $service;
    }
}