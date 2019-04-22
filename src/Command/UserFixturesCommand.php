<?php
/**
 * Copyright © 2019 Vladimir Strackovski <vladimir.strackovski@dlabs.si>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserFixturesCommand
 *
 * @package      App\Command
 * @author       Vladimir Strackovski <vladimir.strackovski@dlabs.si>
 * @copyright    2018 DLabs (https://www.dlabs.si)
 */
class UserFixturesCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'pop:users:in';

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserManager
     */
    protected $manager;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * UserFixturesCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository         $repository
     * @param UserManager            $manager
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repository,
        UserManager $manager
    ) {
        parent::__construct();
        $this->em = $em;
        $this->repository = $repository;
        $this->manager = $manager;
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Creates a set of users.')->setHelp('This command provisions users.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            foreach ($this->generateData() as $user) {
                $u = new User();
                $u
                    ->addRole($user['role'])
                    ->setEnabled(true)
                    ->setFirstName($user['firstname'])
                    ->setLastName($user['lastname'])
                    ->setUsername($user['username'])
                    ->setCountry($user['country'])
                    ->setEmail($user['email'])
                    ->setAvatarUrl($user['avatar_url'])
                    ->setPlainPassword($user['password'])
                ;

                $this->manager->provisionUserDefaults($u);

                $this->repository->save($u);
                $this->repository->save();
            }
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * @return array
     */
    private function generateData()
    {
        return [
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ],
            [
                "username" => $this->faker->userName,
                "email" => $this->faker->email,
                "password" => "password",
                "firstname" => $this->faker->firstName(),
                "lastname" => $this->faker->lastName,
                "country" => $this->faker->countryISOAlpha3,
                "avatar_url" => $this->faker->imageUrl(480),
                "role" => "ROLE_API",
            ]
        ];
    }
}
