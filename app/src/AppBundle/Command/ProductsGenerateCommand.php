<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProductsGenerateCommand
 * @package AppBundle\Command
 */
class ProductsGenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('products:generate')
            ->setDescription('This command generates products');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productService = $this->getContainer()->get('products');
        $priceService = $this->getContainer()->get('prices');
        for ($i=1;$i<=100;$i++) {
            $product = $productService->createProduct("Test {$i}", 'Test ' . md5($i));
        }
    }
}