<?php

namespace AppBundle\Service;

use AppBundle\Traits\ContainerTrait;

/**
 * Class ExchangeRates
 * @package AppBundle\Service
 */
class ExchangeRates
{
    use ContainerTrait;

    public function getValue(float $value, string $currency): float
    {
        if (\AppBundle\Model\Products::DEFAULT_CURRENCY !== $currency) {
            /** @var \AppBundle\Entity\ExchangeRates $rate */
            $rate = $this->getDoctrine()->getRepository('AppBundle:ExchangeRates')->findOneBy([
                'date' => date('Y-m-d'),
                'currency' => $currency,
            ]);
            $value *= $rate->getValue();
        }

        return $value;
    }
}
