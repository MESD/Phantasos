<?php

namespace TyHand\SimpleApiKeyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TyHand\SimpleApiKeyBundle\User\ApiUser;
use TyHand\SimpleApiKeyBundle\Exception\ApplicationNameInUseException;

class AddApiUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('tyhand:simple_apikey:add_api_user')
            ->setDescription('Create a new API user entry')
            ->setDefinition(array(
                new InputArgument('app-name', InputArgument::REQUIRED, 'Application Name'),
                new InputArgument('app-uri', InputArgument::REQUIRED, 'Application URI'),
                new InputArgument('app-desc', InputArgument::REQUIRED, 'Application Description'),
                new InputArgument('cont-email', InputArgument::REQUIRED, 'Contact Email'),
                new InputArgument('cont-name', InputArgument::REQUIRED, 'Contact Name'),
                new InputArgument('keygen', InputArgument::REQUIRED, 'Key Generator')
            ))
            ->setHelp(
                'The <info>tyhand:simple_apikey:add_api_user</info> is a command' .
                ' line tool to add a new api user to the selected storage service' .
                ' and generate a new API key'
            )
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create a new API User and fill out the fields from the inputs
        $apiUser = new ApiUser();
        $apiUser->setApplicationName($input->getArgument('app-name'));
        $apiUser->setApplicationUri($input->getArgument('app-uri'));
        $apiUser->setApplicationDescription($input->getArgument('app-desc'));
        $apiUser->setContactEmail($input->getArgument('cont-email'));
        $apiUser->setContactName($input->getArgument('cont-name'));

        // Check that the application name is unique
        $storage = $this
            ->getContainer()
            ->get('tyhand.simple_apikey.storage_config_handler')
            ->getStorage()
        ;

        if (null !== $storage->loadApiUserByAppName($apiUser->getApplicationName())) {
            throw new ApplicationNameInUseException($apiUser->getApplicationName());
        }

        // Generate the key
        $apiKey = $this
            ->getContainer()
            ->get('tyhand.simple_apikey.generator_manager')
            ->getKeyGenerator($input->getArgument('keygen'))
            ->generateKey()
        ;

        $apiUser->setApiKey($apiKey);

        // Send to storage
        $storage->createNewEntry($apiUser);

        // Print out the API key
        $output->writeln(
            sprintf(
                'Successfully created new entry.  Key "%s"',
                $apiKey
            )
        );
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('app-name')) {
           $appname = $this->getHelper('dialog')->askAndValidate(
               $output,
               'What is the Application Name:   ',
               function($appname) {
                   if (empty($appname)) {
                       throw new \Exception('Application name can not be empty');
                   }
                   return $appname;
                   }
               );
               $input->setArgument('app-name', $appname);
           }

          if (!$input->getArgument('app-uri')) {
              $appuri = $this->getHelper('dialog')->askAndValidate(
                  $output,
                  'What is the Application URI:   ',
                  function($appuri) {
                      if (empty($appuri)) {
                          throw new \Exception('Application uri can not be empty');
                      }
                      return $appuri;
                  }
              );
              $input->setArgument('app-uri', $appuri);
          }

          if (!$input->getArgument('app-desc')) {
             $appdesc = $this->getHelper('dialog')->askAndValidate(
                 $output,
                 'What is the description of the Application:   ',
                 function($appdesc) {
                     if (empty($appdesc)) {
                         throw new \Exception('Application description can not be empty');
                     }
                     return $appdesc;
                 }
             );
             $input->setArgument('app-desc', $appdesc);
          }

          if (!$input->getArgument('cont-email')) {
             $contEmail = $this->getHelper('dialog')->askAndValidate(
                 $output,
                 'Please choose a Contact Email:   ',
                 function($contEmail) {
                     if (empty($contEmail)) {
                         throw new \Exception('Contact Email can not be empty');
                     }
                     return $contEmail;
                 }
             );
             $input->setArgument('cont-email', $contEmail);
          }

          if (!$input->getArgument('cont-name')) {
             $contName = $this->getHelper('dialog')->askAndValidate(
                 $output,
                 'Please choose a Contact Name:   ',
                 function($contName) {
                     if (empty($contName)) {
                         throw new \Exception('Contact name can not be empty');
                     }
                     return $contName;
                 }
             );
             $input->setArgument('cont-name', $contName);
         }

         if (!$input->getArgument('keygen')) {
            $keygen = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a Key Generator <info>[' .
                implode(',', $this
                        ->getContainer()
                        ->get('tyhand.simple_apikey.generator_manager')
                        ->getListOfKeyGeneratorNames()) .
                ']</info>:   ',
                function($keygen) {
                    if (empty($keygen) && !$this->getContainer()->get('tyhand.simple_apikey.generator_manager')->hasDefaultGenerator()) {
                        throw new \Exception('Key generator can not be empty if no default is set');
                    }
                    return $keygen;
                }
            );
            $input->setArgument('keygen', $keygen);
        }
    }
}
