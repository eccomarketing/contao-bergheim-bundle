<?php

declare(strict_types=1);

/**
 * This file is part of Contao Bergheim Bundle.
 *
 * @author      Daniele Sciannimanica  <https://github.com/doishub>
 */

namespace Oveleon\ContaoBergheimBundle\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Oveleon\ContaoBergheimBundle\ContaoBergheimBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @internal
 */
class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoBergheimBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, ContaoCalendarBundle::class])
                ->setReplace(['bergheim-bundle']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Resources/config/routes.yaml')
            ->load(__DIR__.'/../Resources/config/routes.yaml')
            ;
    }
}
