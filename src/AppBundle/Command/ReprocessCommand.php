<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

class ReprocessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('phantasos:reprocess')
            ->setDescription('Reprocess all failed media (or single given media)')
            ->addOption('media-id', null, InputOption::VALUE_OPTIONAL, 'Set if only a single media to reprocess')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // turn off time limit
        set_time_limit(0);

        // Get the storage module and the queuer
        $storage = $this->getContainer()->get('phantasos.storage');
        $queuer = $this->getContainer()->get('phantasos.processor_queuer');

        // Determine if this is a single reprocess or a multitude
        if (null === $input->getOption('media-id')) {
            $media = $storage->getFailedMedia();
        } else {
            $media = array();
            $media[] = $storage->getMediaById($input->getOption('media-id'));
        }

        // Foreach piece of media, requeue
        foreach($media as $m) {
            $queuer->requeueMedia($m->getId());
        }

        // End
        exit();
    }
}
