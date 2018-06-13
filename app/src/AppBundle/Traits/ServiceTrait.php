<?php

namespace AppBundle\Traits;

use AppBundle\Service\AmazonS3Service;
use AppBundle\Service\ExchangeRates;
use AppBundle\Service\Prices;
use AppBundle\Service\Products;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Trait ServiceTrait
 * @package AppBundle\Traits
 */
trait ServiceTrait
{
    /**
     * @return Prices
     */
    public function getPricesService(): Prices
    {
        return $this->container->get('prices');
    }

    /**
     * @return ExchangeRates
     */
    public function getExchangeRatesService(): ExchangeRates
    {
        return $this->container->get('exchange_rates');
    }

    /**
     * @return Products
     */
    public function getProductsService(): Products
    {
        return $this->container->get('products');
    }

    /**
     * @return Registry
     */
    public function getDoctrine(): Registry
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return AmazonS3Service
     */
    public function getAmazonS3Service()
    {
        return $this->container->get('s3_storage');
    }
}