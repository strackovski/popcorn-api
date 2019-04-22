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

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\Manager\ArticleManager;
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
class ArticleFixturesCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'pop:articles:in';

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var ArticleManager
     */
    protected $manager;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * ArticleFixturesCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @param ArticleRepository      $repository
     * @param UserRepository         $userRpository
     * @param ArticleManager         $manager
     */
    public function __construct(
        EntityManagerInterface $em,
        ArticleRepository $repository,
        UserRepository $userRepository,
        ArticleManager $manager
    ) {
        parent::__construct();
        $this->em = $em;
        $this->repository = $repository;
        $this->users = $userRepository;
        $this->manager = $manager;
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Creates a set of articles.')->setHelp('This command provisions articles.')->addArgument(
                'count',
                null,
                'How many',
                10
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = 3;
        if ($input->hasArgument('count') || $input->hasArgument('count') < 20) {
            $count = $input->getArgument('count');
        }
        try {
            for ($i = 1; $i <= $count; $i++) {
                $u = $this->users->findByEmailOrUsername('hello@nv3.eu');
                if (!($u instanceof User)) {
                    return;
                }

                $a = new Article($u);
                $u->addArticle($a);
                $a->setCategory('neutral')->setCtaLink($this->faker->url)->setCtaText($this->faker->words(2, true))
                  ->setImageUrl($this->faker->imageUrl())->setText($this->faker->realText(200, 3))->setText2(
                        $this->faker->realText()
                    )->setTitle($this->faker->words(2, true))
                ;

                $this->repository->save($a);
                $this->repository->save($u);
                $this->repository->save();
            }
        } catch (\Exception $e) {

        }
    }
}
