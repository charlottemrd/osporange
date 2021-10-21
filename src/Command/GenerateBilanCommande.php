<?php

// src/Command/GenerateBilanCommande.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateBilanCommande extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-month-bilanm';
    protected static $defaultDescription = 'Creation de bilan mensuels chaque mois';
    protected function configure(): void
    {
        $this
            // If you don't like using the $defaultDescription static property,
            // you can also define the short description using this method:
            // ->setDescription('...')

            // the command help shown when running the command with the "--help" option
            ->setHelp('Cette commande vous autorise à creer une liste de bilan mensuel vide');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        //$output->writeln($this->someMethod());
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
        // that generates and returns the messages with the 'yield' PHP keyword


        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}