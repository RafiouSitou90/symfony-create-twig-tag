<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct (EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadPost();
    }

    /**
     *
     */
    private function loadPost(): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $post = new Post();

            $post->setTitle(ucfirst($this->faker->realText(100)));
            $post->setDescription(ucfirst($this->faker->realText(191)));
            $post->setContent($this->faker->paragraphs(5, true));
            $post->setCreatedAt($this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'));
            $post->setUpdatedAt($this->faker->dateTimeBetween($startDate = $post->getCreatedAt(), $endDate = '+2 years'));

            $this->manager->persist($post);
        }
        $this->manager->flush();
    }
}
