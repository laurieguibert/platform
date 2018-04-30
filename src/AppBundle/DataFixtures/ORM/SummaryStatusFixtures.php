<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SummaryStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SummaryStatusFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = ['Sommaire en attente de validation', 'Contenu en attente de validation', 'Suppresion en attente', 'Validé', 'Refusé'];
        $i = 0;
        foreach ($data as $d){
            $summaryStatus = new SummaryStatus();
            $summaryStatus->setName($d);
            $manager->persist($summaryStatus);
            $this->addReference('summaryStatus'.$i, $summaryStatus);
            $i ++;
        }

        $manager->flush();
    }
}