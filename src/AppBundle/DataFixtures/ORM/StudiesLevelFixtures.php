<?php
/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 26/06/2018
 * Time: 15:12
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\StudiesLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class StudiesLevelFixtures extends Fixture implements ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [['Licence professionnelle', 'Bac+3'],
            ['Baccalauréat Scientifique', 'Bac S'],
            ['Mastère', 'Bac+5'],
            ['Diplôme universitaire de technologie', 'Bac+2']];
        $i = 0;
        foreach ($data as $d){
            $studiesLevel = new StudiesLevel();
            $studiesLevel->setLevel($d[0]);
            $studiesLevel->setShortLevel($d[1]);
            $manager->persist($studiesLevel);
            $this->addReference('studiesLevel'.$i, $studiesLevel);
            $i ++;
        }

        $manager->flush();
    }
}