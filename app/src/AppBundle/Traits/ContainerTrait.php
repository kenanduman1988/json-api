<?php

namespace AppBundle\Traits;

/**
 * Trait ContainerTrait
 * @package AppBundle\Traits
 */
trait ContainerTrait
{
    use ServiceTrait;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * ContainerTrait constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }
}