<?php

namespace AppBundle\Service;

use AppBundle\Traits\ContainerTrait;

/**
 * Class Prices
 * @package AppBundle\Service
 */
class Prices
{
    use ContainerTrait;

    /**
     * @param \AppBundle\Entity\Products $product
     * @param string $iso3
     * @param float $value
     * @return \AppBundle\Entity\Prices
     */
    public function createPrice(\AppBundle\Entity\Products $product, string $iso3, float $value): \AppBundle\Entity\Prices
    {
        $priceEntity = new \AppBundle\Entity\Prices();
        $priceEntity
            ->setProduct($product)
            ->setIso3($iso3)
            ->setValue($value)
        ;
        $this->getDoctrine()->getManager()->persist($priceEntity);
        $this->getDoctrine()->getManager()->flush();

        return $priceEntity;
    }
}

