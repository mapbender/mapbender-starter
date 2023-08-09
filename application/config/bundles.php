<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
    Symfony\Bundle\AclBundle\AclBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Mapbender\CoordinatesUtilityBundle\MapbenderCoordinatesUtilityBundle::class => ['all' => true],
    Mapbender\DataSourceBundle\MapbenderDataSourceBundle::class => ['all' => true],
    Mapbender\DataManagerBundle\MapbenderDataManagerBundle::class => ['all' => true],
    Mapbender\DigitizerBundle\MapbenderDigitizerBundle::class => ['all' => true],
];
