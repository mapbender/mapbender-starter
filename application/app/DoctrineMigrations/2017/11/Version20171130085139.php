<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171130085139 extends AbstractMigration
{
    private $configuration = [
        'className'    => 'Mapbender\CoreBundle\Element\Map',
        'oldImagePath' => 'components/mapquery/lib/openlayers/img',
        'newImagePath' => 'components/mapquery/lib/openlayers/my-new-img',
    ];

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this
            ->addSql(
                "UPDATE mb_core_element SET configuration = REPLACE(configuration, :oldImagePath, :newImagePath) WHERE class = :className",
                $this->configuration
            );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this
            ->addSql(
                "UPDATE mb_core_element SET configuration = REPLACE(configuration, :newImagePath, :oldImagePath) WHERE class = :className",
                $this->configuration
            );

    }
}
