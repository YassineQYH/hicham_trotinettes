<?php

namespace App\Command;

use App\Service\DeliveryChecker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-deliveries',
    description: 'Vérifie les livraisons via Track123 et met à jour les commandes'
)]
class CheckDeliveriesCommand extends Command
{
    private DeliveryChecker $deliveryChecker;

    public function __construct(DeliveryChecker $deliveryChecker)
    {
        parent::__construct();
        $this->deliveryChecker = $deliveryChecker;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('🔍 Début de la vérification des livraisons...');
        $this->deliveryChecker->checkDeliveries();
        $output->writeln('✅ Vérification des livraisons terminée.');
        return Command::SUCCESS;
    }
}
