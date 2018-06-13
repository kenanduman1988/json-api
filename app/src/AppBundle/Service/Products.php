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
            ->setCreatedAt(date('Y-m-d'))
        ;
        $this->getDoctrine()->getManager()->persist($productEntity);
        $this->getDoctrine()->getManager()->flush();

        return $productEntity;
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getList(string $from, string $to): array
    {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder('AppBundle:Products p')
            ->where('createdAt >= :from')
            ->andWhere('createdAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
        ;

        return $qb->execute() ?? [];

    }
}