<?php

namespace TyHand\SimpleApiKeyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TyHand\SimpleApiKeyBundle\User\ApiUser;
use TyHand\SimpleApiKeyBundle\Exception\ApplicationNameInUseException;

class SetActiveStatusCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('tyhand:simple_apikey:set_active_status')
            ->setDescription('Get the current API key for the given Application')
            ->addOption('app-name', null, InputOption::VALUE_REQUIRED, 'Application Name')
            ->addOption('active', null, InputOption::VALUE_REQUIRED, 'Flag to set active to')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the app name
        $appName = $input->getOption('app-name');
        $active = filter_val($input->getOption('active'), FILTER_VALIDATE_BOOLEAN);

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
            $output->writeln(sprintf('<error>No registration exists for %s</error>', $appName));
        } else {
            $storage->changeActiveStatus($appName, $active);
        }
    }
}
