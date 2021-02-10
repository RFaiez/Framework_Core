<?php

namespace Command;

use Database\Singleton\MysqlConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreate extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('database:create');
        $this->setDescription('Create a database');
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
        $mysqlConnection->exec("Create Database ".$_ENV['DB_NAME']);
        $output->writeln('the database '.$_ENV['DB_NAME'].' has been created successfully');
        return 0;
    }
}

