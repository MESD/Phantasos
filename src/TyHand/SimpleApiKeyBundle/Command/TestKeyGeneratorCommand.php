<?php

namespace TyHand\SimpleApiKeyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestKeyGeneratorCommand extends ContainerAwareCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('tyhand:simple_apikey:test_keygen')
            ->setDescription('Generate an API key to the screen for testing')
            ->addOption('keygen', null, InputOption::VALUE_REQUIRED, 'keygen to test')
        ;
    }

    /**
     * Test an API key generator
     *
     * @param  InputInterface  $input  The input interface
     * @param  OutputInterface $output The output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the name from the input
        $name = $input->getOption('keygen');

        // Get the service and generate a key
        $key = $this
            ->getContainer()
            ->get('tyhand.simple_apikey.generator_manager')
            ->getKeyGenerator($name)
            ->generateKey()
        ;

        // Print out the key
        $output->writeln(sprintf('Key: %s', $key));
    }
}
