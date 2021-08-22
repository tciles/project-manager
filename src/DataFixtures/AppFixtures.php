<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectTag;
use App\Entity\ProjectVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $tagsNames = ['', '-beta', '-rc'];

        $tags = [
            ['stable', '#1279ff'],
            ['beta', '#dc3545'],
            ['release candidate', '#28a745'],
            ['deprecated', '#6c757d'],
            ['unmaintained', '#ffc107'],
        ];

        foreach ($tags as $k => $item) {
            [$name, $color] = $item;

            $tag = new ProjectTag();
            $tag->setName($name)
                ->setColor($color);

            $manager->persist($tag);

            $tags[$k] = $tag;
        }

        $manager->flush();

        for ($i = 0; $i < 20; $i++) {
            $project = new Project();
            $project->setTitle($faker->words(3, true))
                ->setDescription($faker->words(30, true))
                ->setActive($faker->boolean());

            $manager->persist($project);

            for ($j = 0; $j < 5; $j++) {
                $version = new ProjectVersion();
                $version->setName("1.0.$j".$faker->randomElement($tagsNames))
                    ->setProject($project)
                    ->setTags(new ArrayCollection([$faker->randomElement($tags)]));

                $manager->persist($version);
            }
        }

        $manager->flush();
    }
}
