<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SaveSluTestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:save_slu_test')
            ->setDescription('browse file and save questions');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit',-1);
        $output->writeln([
            'save test',
            '============',
            '',
        ]);

        $this->getContainer()->get('extract.test')->extractTestNode();

        $output->writeln('All test are saving!');
    }
}
