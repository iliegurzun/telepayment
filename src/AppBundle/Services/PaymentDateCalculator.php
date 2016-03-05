<?php

/**
 * Created by PhpStorm.
 * User: Ilie
 * Date: 5/3/2016
 * Time: 2:49 PM
 */

namespace AppBundle\Services;

/**
 * Service for calculating payment dates for salaries and bonuses
 *
 * Class PaymentDateCalculator
 * @package AppBundle\Services
 */
class PaymentDateCalculator
{
    /** @const string */
    const SERVICE_NAME = 'app.payment_date_calculator';

    /** @const string */
    const DAY_SATURDAY = 'Saturday';

    /** @const string */
    const DAY_SUNDAY = 'Sunday';

    /**
     * Calculates the payment dates and generates the CSV file with
     * all payment dates for the next 12 months
     *
     * @return string
     */
    public function execute()
    {
        $dates = $this->calculatePaymentDates();
        $file = $this->generateCsv($dates);

        return $file;
    }

    /**
     * Calculate the payment and bonuses dates for the next 12 months
     *
     * @return array
     */
    public function calculatePaymentDates()
    {
        $paymentDates = array();
        $months = $this->getNextMonths();
        foreach ($months as $key => $month) {
            $lastDay = $month->modify('last day of this month');
            $lastDayString = $lastDay->format('l');
            if (in_array($lastDayString, array(self::DAY_SATURDAY, self::DAY_SUNDAY))) {
                $lastDay = $lastDay->modify('previous friday');
            }
            $paymentDates[$key] = array(
                'month' => $lastDay->format('F'),
                'base'  => $lastDay->format('Y-m-d'),
                'bonus' => $this->getBonusDate($month)
            );
        }

        return $paymentDates;
    }

    /**
     * Returns the bonus day for a month
     *
     * @param \DateTime $month
     * @return \DateTime
     */
    public function getBonusDate(\DateTime $month)
    {
        $bonusDate = new \DateTime(sprintf('%s-12', $month->format('Y-m')));
        $bonusDay = $bonusDate->format('l');
        if (in_array($bonusDay, array(self::DAY_SATURDAY, self::DAY_SUNDAY))) {
            $bonusDate = $bonusDate->modify('next tuesday');
        }

        return $bonusDate->format('Y-m-d');
    }

    /**
     * Returns the next 12 months
     *
     * @return array
     */
    public function getNextMonths()
    {
        $months = array();
        $currentMonth = $this->getCurrentMonth();
        for ($x = $currentMonth+1; $x <= $currentMonth + 12; $x++) {
            $dtStr = date('c', mktime(0, 0, 0, $x, 1));
            $date = new \DateTime($dtStr);
            $months[] = $date;
        }

        return $months;
    }

    /**
     * Returns the current month number
     * Moved into separate function for test purpose
     * @return int
     */
    public function getCurrentMonth()
    {
        return intval(date('m'));
    }

    /**
     * @param array $dates
     * @return string
     */
    public function generateCsv(array $dates)
    {
        $fp = fopen('file.csv', 'w');
        foreach ($dates as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return realpath('file.csv');
    }
}