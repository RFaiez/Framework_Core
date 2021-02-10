<?php

namespace Command;

use Database\Singleton\MysqlConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDrop extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('database:drop');
        $this->setDescription('Drop a database');
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mysqlConnection=MysqlConnection::getInstance();
        $mysqlConnection->exec("drop Database ".$_ENV['DB_NAME']);
        $output->writeln('the database '.$_ENV['DB_NAME'].' has been dropped');
        return 0;
    }
}

