<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $hash;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->hash = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        for($i =0 ; $i<10 ; $i++){
            $user = new User();
            $user->setEmail('user' . $i . '@gmail.com')
                 ->setPrenom('user'.$i)  
                 ->setNom('userNom'.$i) 
                 ->setClasse('DSI'.$i)
                 ->setTelephone('2515457'.$i)
                 ->setPassword($this->hash->encodePassword($user,'0'))
                 ->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        $admin = new User();
        $admin->setEmail('admin@gmail.com')
              ->setPassword($this->hash->encodePassword($user,'0'))
              ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

    }
}
