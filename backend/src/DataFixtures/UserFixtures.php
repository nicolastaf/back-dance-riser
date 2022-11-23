<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\DataFixtures\LevelFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $user_reference = [];
        
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstname($faker->firstName);
        $admin->setLastname($faker->lastName);
        $admin->setActivated(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setCreatedAt(new DateTimeImmutable());
        $user_reference['user_1'] = $admin;
        $admin->setLevel($this->getReference('level_' . rand(1, 4)));
        // mot de passe = admin
        $admin->setPassword('$2y$13$/LRHx9AA56jotW5UV40BjeB1N5NU4zkMyD34lOv8Lb8ozBDVpbh2u');
        $manager->persist($admin);

        $managerUser = new User();
        $managerUser->setEmail('manager@manager.com');
        $managerUser->setFirstname($faker->firstName);
        $managerUser->setLastname($faker->lastName);
        $managerUser->setActivated(true);
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setCreatedAt(new DateTimeImmutable());
        $user_reference['user_2'] = $managerUser;
        $managerUser->setLevel($this->getReference('level_' . rand(1, 4)));
        // mot de passe = manager
        $managerUser->setPassword('$2y$13$A30us9hMs04OMDrp387iiOzgpyN1RxWhQNE3DcFwsNhN9O0DYugdW');
        $manager->persist($managerUser);

        for ($i = 3; $i <= 6; $i++) {
            $member = new User();
            $member->setEmail('member' . $i . '@member.com');
            $member->setFirstname($faker->firstName);
            $member->setLastname($faker->lastName);
            $member->setActivated(true);
            $member->setRoles(['ROLE_MEMBER']);
            $member->setCreatedAt(new DateTimeImmutable());
            $user_reference['user_' . $i] = $member;
            $member->setLevel($this->getReference('level_' . rand(1, 4)));
            // mot de passe = user
            $member->setPassword('$2y$13$OX9RoBNejyEYZaMx9JmR8Ogw5AIDWPRSmrwmf8To9fv6CuiFa4r2C');

            $manager->persist($member);
        }

        $manager->flush();

        foreach ($user_reference as $key => $item) {
            $this->addReference($key, $item);
        }
    }

    public function getDependencies()
    {
        return array(
            LevelFixtures::class,
        );
    }
}
