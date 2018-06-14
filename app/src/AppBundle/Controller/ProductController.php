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
        $data = json_decode($request->getContent(), true);
        if (empty($data['sku']) && empty($data['name']) && empty($data['price'])) {
            return new Response(406, 'Not acceptible request');
        }
        $productEntity = $this->getProductsService()->createProduct($data['sku'], $data['name']);
        $value = $this->getExchangeRatesService()->getValue($data['price']['value'], $data['price']['currency']);
        $this->getPricesService()->createPrice($productEntity, $data['price']['currency'], $value);
        $this->getDoctrine()->getManager()->clear();

        return new JsonResponse('OK');
    }

    /**
     * @Route("/list", name="list")
     * @param Request $request
     * @return string
     */
    public function listAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $from = empty($data['from']) ? $request->get('from', null) : $data['from'];
        $to = empty($data['to']) ? $request->get('to', null) : $data['to'];

        return new Response($this->getAmazonS3Service()->getUrl(
            $from,
            $to,
            $this->getProductsService()->getList($from, $to)
        ), 200);
    }
}
