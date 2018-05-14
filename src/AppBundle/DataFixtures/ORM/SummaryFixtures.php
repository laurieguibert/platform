<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Summary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SummaryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $summary = new Summary();
            $summary->setTitle("Title" . $i);
            $summary->setContent("Content" . $i);
            $summary->setStatus($this->getReference('summaryStatus'.$i));
            $manager->persist($summary);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SummaryStatusFixtures::class
        );
    }
}