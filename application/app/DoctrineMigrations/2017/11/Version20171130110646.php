<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

use Mapbender\CoreBundle\Entity\Element;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171130110646 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function postUp(Schema $schema)
    {
        $output = new ConsoleOutput();

        /**
         * @var EntityManager $em
         */
        $em = $this
            ->container
            ->get('doctrine.orm.entity_manager');

        $maps = $em
            ->getRepository('MapbenderCoreBundle:Element')
            ->findBy(
                array(
                    'class' => $this->configuration['className'],
                )
            );

        $output->writeln('Updating map elements image path values');
        $output->writeln('Found ' . count($maps) . ' map elements');

        $progressBar = new ProgressBar($output, count($maps));

        /**
         * @var Element $map
         */
        foreach ($maps as $map) {
            $config = $map->getConfiguration();
            $progressBar->advance();

            if ($config['imgPath'] == $this->configuration['oldImagePath']) {
                $progressBar->setMessage('Found old image path');

                $config['imgPath']= $this->configuration['newImagePath'];

                $map->setConfiguration($config);
                $em->persist($map);

                $progressBar->setMessage('Old image path successfully changed');
            } else {
                $progressBar->setMessage('Map element already up-to-date');
            }
        }

        $em->flush();

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('All image path values are now up-to-date');
        $output->writeln('Exiting now');
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
