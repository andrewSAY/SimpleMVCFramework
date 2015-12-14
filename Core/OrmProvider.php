<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 24.08.15
 * Time: 10:19
 */

namespace LW\Core;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class OrmProvider
{
    /**
     * @var EntityManager $em
     */
    private $em;

    function __construct()
    {
        global $CONFIG;
        $cashDriver = new ArrayCache();
        $dbParams = $CONFIG['DB_CONFIG'];
        $ormConfig  = Setup::createAnnotationMetadataConfiguration(array(PATH_TO_ENTITIES_ORM), false, SITE_PATH.DS.$CONFIG['CACHE_FOLDER_NAME'].DS.'doctrine', null, false);
        $ormConfig->setProxyNamespace('LW\Cache\Doctrine');
        $ormConfig->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_NEVER);
        $ormConfig->setMetadataCacheImpl($cashDriver);
        $ormConfig->setQueryCacheImpl($cashDriver);
        $this->em = EntityManager::create($dbParams, $ormConfig);
    }

    public function getEntityManager()
    {
        return $this->em;
    }
} 