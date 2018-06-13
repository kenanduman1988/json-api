<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Prices;
use AppBundle\Entity\Products;
use AppBundle\Traits\ServiceTrait;
use Doctrine\Common\Persistence\ObjectManager;
use Proxies\__CG__\AppBundle\Entity\ExchangeRates;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/product", name="product")
 * Class ProductController
 * @package AppBundle\Controller
 */
class ProductController extends Controller
{
    use ServiceTrait;

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $sku = $request->get('sku', null);
        $name = $request->get('name', null);
        $price = $request->get('price', null);
        if ($sku && $name && $price) {
            $productEntity = $this->getProductsService()->createProduct($sku, $name);
            $value = $this->getExchangeRatesService()->getValue($price['price'], $price['currency']);
            $priceEntity = $this->getPricesService()->createPrice($productEntity, $price['currency'], $value);
        }

        if($productEntity && $priceEntity) {
            $this->getDoctrine()->getManager()->clear();
            
            return new JsonResponse('OK');
        }

        return new Response(406, 'Not acceptible request');
    }

    /**
     * @Route("/list", name="list")
     * @param Request $request
     * @return string
     */
    public function getList(Request $request): string
    {
        $fileSystem = new Filesystem();
        /** @var string $from */
        $from = $request->get('from', null);
        /** @var string $to */
        $to = $request->get('to', null);
        if ($from && $to) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder('AppBundle:Products p')
                ->where('createdAt >= :from')
                ->andWhere('createdAt <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to)
                ->getQuery()
            ;
            $list = $qb->execute();
            $tmpFile = '/tmp/s3.csv';
            $handle = fopen($tmpFile, 'w+');
            // insert header
            fputcsv($handle, [
                'sku',
                'name',
                'price_eur',
                'created_at',
            ], ';');
            foreach ($list as $item) {
                // insert rows
                fputcsv($handle, [
                    $item['sku'],
                    $item['name'],
                    $item['price'],
                    $item['created_at'],
                ], ';');
            }
            fclose($handle);
            $s3Url = $this->getAmazonS3Service()->uploadFile(
                $tmpFile,
                sprintf( 'products_%s_%s', $from, $to)
            );
            $fileSystem->remove($tmpFile);
            if ($s3Url) {

                return new JsonResponse($s3Url);
            }
        }

        return new Response('Error', 500);
    }
}
