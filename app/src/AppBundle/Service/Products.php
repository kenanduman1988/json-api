<?php

namespace AppBundle\Service;

use AppBundle\Traits\ContainerTrait;

/**
 * Class Products
 * @package AppBundle\Service
 */
class Products
{
    use ContainerTrait;

    /**
     * @param string $name
     * @param string $sku
     * @return \AppBundle\Entity\Products
     */
    public function createProduct(string $name, string $sku): \AppBundle\Entity\Products
    {
        $productEntity = new \AppBundle\Entity\Products();
        $productEntity
            ->setName($name)
            ->setSku($sku)
            ->setCreatedAt(new \DateTime())
        ;
        $this->getDoctrine()->getManager()->persist($productEntity);
        $this->getDoctrine()->getManager()->flush();

        return $productEntity;
    }

    /**
     * @param string $from
     * @param string $to
     * @return \AppBundle\Entity\Products[]
     */
    public function getList(string $from, string $to): array
    {
        $qb = $this->getDoctrine()->getEntityManager()->createQuery("
            SELECT p 
              FROM AppBundle:Products p
              WHERE p.createdAt BETWEEN :from AND :to
        ")
            ->setParameter('from', $from)
            ->setParameter('to', $to)
        ;

        return $qb->getResult() ?? [];
    }
}
