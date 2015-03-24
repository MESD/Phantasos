<?php

namespace TyHand\SimpleApiKeyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TyHand\SimpleApiKeyBundle\User\ApiUser;
use TyHand\SimpleApiKeyBundle\Exception\ApplicationNameInUseException;

class GetApiKeyForAppCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('tyhand:simple_apikey:get_api_key')
            ->setDescription('Get the current API key for the given Application')
            ->addOption('app-name', null, InputOption::VALUE_REQUIRED, 'Application Name')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the app name
        $appName = $input->getOption('app-name');

        // Get the storage system
        $storage = $this
            ->getContainer()
            ->get('tyhand.simple_apikey.storage_config_handler')
            ->getStorage()
        ;

        // Get the api key
        $apiUser = $storage->loadApiUserByAppName($appName);

        // Print out the API key
        if (null === $apiUser) {
            $output->writeln(sprintf('<error>No key exists for %s</error>', $appName));
        } else {
            $output->writeln(sprintf('Key: %s', $apiUser->getApiKey()));
        }
    }
}
