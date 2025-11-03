<?php

namespace RaifuCore\Phone\Console;

use Illuminate\Console\Command;
use RaifuCore\Phone\Enums\ProviderLabelEnum;
use RaifuCore\Phone\PhoneModule;

class BalanceCommand extends Command
{
    protected $signature = 'phone:balance {--provider=}';

    protected $description = 'Show provider\'s balance. Provide phone';

    public function handle(): int
    {
        // Detect phone
        $oProvider = $this->option('provider');

        if ($oProvider) {
            $providerLabel =  ProviderLabelEnum::tryFrom($oProvider);
            if (!$providerLabel) {
                $this->error("Provider {$oProvider} is not supported");
                return Command::FAILURE;
            }
            $this->_exec($providerLabel);
        } else {
            foreach (ProviderLabelEnum::cases() as $providerLabel) {
                $this->_exec($providerLabel);
            }
        }

        return Command::SUCCESS;
    }

    private function _exec(ProviderLabelEnum $providerLabel): void
    {
        try {
            $provider = PhoneModule::getProvider($providerLabel);
            $this->info("Provider {$providerLabel->value}. Balance: " . $provider->getBalance());
        } catch (\Throwable $e) {
            $this->error("Provider {$providerLabel->value}. Error: " . $e->getMessage());
        }
    }
}
