<?php

namespace TyHand\SimpleApiKeyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TyHand\SimpleApiKeyBundle\User\ApiUser;

class UpdateKeyForAppCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('tyhand:simple_apikey:update_key')
            ->setDescription('Get the current API key for the given Application')
            ->addOption('app-name', null, InputOption::VALUE_REQUIRED, 'Application Name')
            ->addOption('keygen', null, InputOption::VALUE_OPTIONAL, 'The keygen to use')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the app name
        $appName = $input->getOption('app-name');
        $keygen = $input->getOption('keygen');

        // Get the storage system
        $storage = $this
            ->getContainer()
            ->get('tyhand.simple_apikey.storage_config_handler')
            ->getStorage()
        ;

        // Check that the user is real
        $apiUser = $storage->loadApiUserByAppName($appName);

        // Print out the API key
        if (null === $apiUser) {
            $output->writeln(
                sprintf(
                    '<error>No registration exists for %s</error>',
                    $appName
                )
            );
        } else {
            // Generate a new key
            $key = $this
                ->getContainer()
                ->get('tyhand.simple_apikey.generator_manager')
                ->getKeyGenerator($keygen)
                ->generateKey()
            ;

            // Tell the storage machinery to update the api key
            $storage->updateApiKey($appName, $key);

            // Print out the key
            $output->writeln(sprintf('New Key: %s', $key));
        }
    }
}
