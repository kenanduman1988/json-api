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
    public function listAction(Request $request): string
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        if (null === $from || null === $to) {
            return new Response('Not enough parameter', 406);
        }

        return new JsonResponse($this->getAmazonS3Service()->getUrl(
                $from,
                $to,
                $this->getProductsService()->getList($from, $to)
            ));

    }
}
