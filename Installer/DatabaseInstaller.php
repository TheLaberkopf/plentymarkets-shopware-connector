<?php

namespace PlentyConnector\Installer;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use PlentyConnector\Connector\Config\Model\Config;
use PlentyConnector\Connector\Identity\Model\Identity;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

/**
 * Class Database
 *
 * @package PlentyConnector\Install
 */
class DatabaseInstaller implements InstallerInterface
{

    /**
     * @var AbstractSchemaManager
     */
    private $schemaTool;

    /**
     * @var ClassMetadata[]
     */
    private $models = [];

    /**
     * DatabaseInstaller constructor.
     *
     * @param ModelManager $entitiyManager
     */
    public function __construct(ModelManager $entitiyManager)
    {
        $this->schemaTool = new SchemaTool($entitiyManager);

        $models = [
            Config::class,
            Identity::class
        ];

        foreach ($models as $model) {
            $this->models[] = $entitiyManager->getClassMetadata($model);
        }
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $this->schemaTool->updateSchema($this->models, true);
    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        $this->schemaTool->updateSchema($this->models, true);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        if (!$context->keepUserData()) {
            $this->schemaTool->dropSchema($this->models);
        }
    }
}
