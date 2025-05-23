<?php

namespace Leezy\PheanstalkBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NextReadyCommand extends AbstractPheanstalkCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('leezy:pheanstalk:next-ready')
            ->addArgument('tube', InputArgument::REQUIRED, 'Tube to get next ready.')
            ->addOption(
                'details',
                null,
                InputOption::VALUE_NONE,
                'Display details'
            )
            ->addArgument('pheanstalk', InputArgument::OPTIONAL, 'Pheanstalk name.')
            ->setDescription('Gives the next ready job from a specified tube.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('pheanstalk');
        $tubeName = $input->getArgument('tube');

        $pheanstalk = $this->getPheanstalk($name);

        $output->writeln('Pheanstalk: <info>' . $name . '</info>');

        $nextJobReady = $pheanstalk->useTube($tubeName)->peekReady();
        if (null === $nextJobReady) {
            $output->writeln('There is no next ready job in this tube: <info>' . $tubeName . '</info>');

            return 1;
        }

        $nextJobReadyId = $nextJobReady->getId();
        $nextJobReadyData = $nextJobReady->getData();

        $output->writeln(
            sprintf('Next ready job in tube <info>%s</info> is <info>%s</info>', $tubeName, $nextJobReadyId)
        );

        if ($input->getOption('details')) {
            $output->writeln('Details:');
            $output->writeln($nextJobReadyData);
        }

        return 0;
    }
}
