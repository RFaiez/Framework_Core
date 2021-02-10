<?php

namespace rfaiez\framework_core\Command;

use rfaiez\framework_core\Database\Singleton\MysqlConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDrop extends Command
{
    /**
     * Configure.
     *
     * @return void
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = MysqlConnection::getInstance();
        $mysqlConnection->exec('drop Database '.$_ENV['DB_NAME']);
        $output->writeln('the database '.$_ENV['DB_NAME'].' has been dropped');

        return 0;
    }
}
