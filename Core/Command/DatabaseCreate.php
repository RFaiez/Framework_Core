<?php

namespace rfaiez\framework_core\Command;

use rfaiez\framework_core\Database\Singleton\MysqlConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreate extends Command
{
    /**
     * Configure.
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('database:create');
        $this->setDescription('Create a database');
    }

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = MysqlConnection::getInstance();
        $mysqlConnection->exec('Create Database '.$_ENV['DB_NAME']);
        $output->writeln('the database '.$_ENV['DB_NAME'].' has been created successfully');

        return 0;
    }
}
