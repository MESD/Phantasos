<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

use OldSound\RabbitMqBundle\Command\ConsumerCommand;

class RunProcessorsCommand extends ContainerAwareCommand
{
    protected $logger;
    protected $messagePool;
    protected $emailTimeout;
    protected $lastEmail;

    protected function configure()
    {
        $this
            ->setName('phantasos:run_processors')
            ->setDescription('Run the phantasos processors')
            ->addOption('email-timeout', null, InputOption::VALUE_OPTIONAL, 'Number of seconds between emails', 1800)
            ->addOption('timeout', null, InputOption::VALUE_OPTIONAL, 'Number of seconds between checks', 60)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // turn off time limit
        set_time_limit(0);

        // init
        if (file_exists(getcwd() . '/last_alert_email.txt')) {
            $this->lastEmail = intval(file_get_contents(getcwd() . '/last_alert_email.txt'));
        } else {
            $this->lastEmail = null;
        }

        $this->messagePool = array();

        // Get the options
        $this->emailTimeout = intval($input->getOption('email-timeout'));
        $timeout = intval($input->getOption('timeout'));

        // Get the logger
        $this->logger = $this->getContainer()->get('logger');

        // Get the number of processors to run
        $num = $this->getContainer()->getParameter('phantasos_processor_count');

        // See if debug is on
        $debug = (OutputInterface::VERBOSITY_VERBOSE == $output->getVerbosity());

        // Init the children array
        $children = array();

        // If debug, print message
        if ($debug) {
            $output->writeln('Starting...');
        }

        // enter the endless loop of doom!
        try {
            while(true) {
                // If the number of children is less than the number of num, boot
                while(count($children) < $num) {
                    // FORK!!!
                    $pid = pcntl_fork();

                    // Check the result of the fork
                    if (-1 === $pid) {
                        // FAIL

                        throw new \Exception('Failed to fork process');
                    } elseif (0 < $pid) {
                        // PARENT

                        // Add new child
                        $children[] = $pid;

                        // If debug, print message
                        if ($debug) {
                            $output->writeln('Starting Processor Process ' . $pid);
                        }
                    } else {
                        // BABY

                        // Create a new rabbitmq consumer command
                        $command = new ConsumerCommand();
                        $command->setContainer($this->getContainer());
                        $input = new ArrayInput(array(
                            'name' => 'process_upload',
                            '--without-signals' => true
                        ));
                        $output = new NullOutput();

                        // Run the command
                        try {
                            $command->run($input, $output);
                        } catch (\Exception $e) {
                            // Send out the alert
                            $this->sendAlert($e->getMessage());

                            // Print it if in debug mode
                            if ($debug) {
                                $output->writeln('<error>' . $e->getMessage() . '</error>');
                            }
                        }

                        exit();
                    }
                }

                // Check all the children
                foreach($children as $index => $pid) {
                    $res = pcntl_waitpid($pid, $status, WNOHANG);
                    if (-1 === $res || 0 < $res) {
                        // Remove child
                        unset($children[$index]);

                        // If debug, print message
                        if ($debug) {
                            $output->writeln('Processor Process ' . $pid . ' has stopped');
                        }
                    }
                }

                sleep($timeout);
            }
        } catch (\Exception $e) {
            // Send out an error message
            $this->sendAlert($e->getMessage());

            // Print the error to the screen if in debug mode
            if ($debug) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
        }

        if ($debug) {
            $output->writeln('Stopped');
        }

        // End
        exit();
    }

    protected function sendAlert($message)
    {
        $this->messagePool[] = $message;
        $now = time();
        if (null === $this->lastEmail || $now >= ($this->emailTimeout + $this->lastEmail)) {
            $this->logger->critical(implode('<br>', $this->messagePool));
            $this->lastEmail = time();
            file_put_contents(getcwd() . '/last_alert_email.txt', $this->lastEmail);
            $this->messagePool = array();
        }
    }
}
