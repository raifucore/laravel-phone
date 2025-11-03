<?php

namespace RaifuCore\Phone\Console;

use Illuminate\Console\Command;
use RaifuCore\Phone\PhoneModule;

class BalanceCommand extends Command
{
    protected $signature = 'phone:balance {--provider}';

    protected $description = 'Show provider\'s balance. Provide phone';

    public function handle(): int
    {
        // Detect phone
        $provider = $this->option('provider');
        if (!$provider) {
            $this->error('Phone number is empty');
            return Command::INVALID;
        }

        $dto = PhoneModule::getDtoByPhone($phone);
        if (!$dto) {
            $this->error('Provided phone number is not valid');
            return Command::FAILURE;
        } else {
            $this->info("Phone number +{$dto->getFull()} belongs to country " . strtoupper($dto->getCountryIso()));
            return Command::SUCCESS;
        }
    }
}
