<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Schema\Table;

$console = new Application('Blog', '1.0');

//$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
//$console->setDispatcher($app['dispatcher']);

$console
    ->register('crear_base_datos')
    ->setDefinition(array())
    ->setDescription('Crea las tablas adecuadas en la base de datos. Si ya existía la tabla, NO se borra ')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        
        $schema = $app['db']->getSchemaManager();

        if(!$schema->tablesExist('articles'))
        {
        	$articles = new Table('articles');
        	$articles->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        	$articles->setPrimaryKey(array('id'));
        	$articles->addColumn('title', 'string', array('length' => 255));
        	$articles->addColumn('content', 'string', array('lenght' => 5000));
        	$articles->addColumn('created', 'datetime');

        	$schema->createTable($articles);
        	$output->writeln("<info>schema realizado con éxito.</info>");
        }
        else
        {
        	$output->writeln("<info>El schema ya existe.</info>");
        }
    })
;

return $console;
