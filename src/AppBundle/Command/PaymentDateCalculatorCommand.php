<?php

/**
 * Created by PhpStorm.
 * User: Ilie
 * Date: 5/3/2016
 * Time: 1:48 PM
 */
namespace AppBundle\Command;

use AppBundle\Services\PaymentDateCalculator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for generating a CSV file with the each month's salary and bonus dates
 *
 * Class PaymentDateCalculatorCommand
 * @package AppBundle\Command
 */
class PaymentDateCalculatorCommand extends ContainerAwareCommand
{
    /**
     * Command configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:calculate_payment_date')
            ->setDescription('Calculates the date of each month\'s salary payment and bonuses')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PaymentDateCalculator $service */
        $service = $this->getContainer()->get(PaymentDateCalculator::SERVICE_NAME);
        try {
            $filepath = $service->execute();
            $output->writeln(sprintf('<info>File "%s" was created.</info>', $filepath));
        } catch (\Exception $ex) {
            $output->writeln(sprintf('<error>%s</error>', $ex->getMessage()));
        }
    }
}