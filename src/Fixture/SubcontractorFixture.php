<?php

namespace App\Fixture;

use App\Entity\Subcontractor;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class SubcontractorFixture extends AbstractFixture
{
    /**
     * @var FactoryInterface
     */
    private $subcontractorFactory;
    /**
     * @var ObjectManager
     */
    private $subcontractorManager;
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(
        FactoryInterface $subcontractorFactory,
        ObjectManager $subcontractorManager,
        Generator $faker
    ){
        $this->subcontractorFactory = $subcontractorFactory;
        $this->subcontractorManager = $subcontractorManager;
        $this->faker = $faker;
    }

    public function load(array $options): void
    {
        for ($i = 0; $i < $options['count']; $i++) {
            /** @var Subcontractor $subcontractor */
            $subcontractor = $this->subcontractorFactory->createNew();
            $subcontractor->setName($this->faker->company);
            $subcontractor->setEmail($this->faker->companyEmail);

            $this->subcontractorManager->persist($subcontractor);
        }

        $this->subcontractorManager->flush();
    }

    public function getName(): string
    {
        return 'subcontractor';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode->children()->integerNode('count');
    }
}
