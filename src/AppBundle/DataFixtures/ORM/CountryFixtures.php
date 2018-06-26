<?php
/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 26/06/2018
 * Time: 11:43
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = [[4, 'AF', 'AFG', 'Afghanistan', 'Afghanistan'],
            [8, 'AL', 'ALB', 'Albania', 'Albanie'],
            [10, 'AQ', 'ATA', 'Antartica', 'Antarctique'],
            [20, 'AO', 'AGO', 'Angola', 'Angola']];

        $i = 0;
        foreach ($data as $d){
            $country = new Country();
            $country->setCode($d[0]);
            $country->setAlpha2($d[1]);
            $country->setAlpha3($d[2]);
            $country->setEnName($d[3]);
            $country->setFrName($d[4]);
            $manager->persist($country);
            $this->addReference('country'.$i, $country);
            $i ++;
        }

        $manager->flush();
    }
}