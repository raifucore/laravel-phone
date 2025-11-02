<?php

namespace RaifuCore\Phone\Console;

use App\Helpers\PhoneHelper;
use App\Modules\Users\Actions\UserCreateAction;
use App\Modules\Users\Enums\UserRoleEnum;
use App\Modules\Users\Exceptions\AlreadyExistsPhoneException;
use App\Modules\Users\Requests\UserCreateRequestDto;
use Illuminate\Console\Command;
use RaifuCore\Phone\PhoneModule;

class ValidateCommand extends Command
{
    protected $signature = 'phone:validate {--phone=}';

    protected $description = 'Validate phone. Provide --phone=';

    public function handle(): void
    {
        // Detect phone
        $phone = preg_replace('[\D]', '', $this->option('phone'));
        if (!$phone) {
            $this->error('Provide a phone number by option --phone=');
            return;
        }

        $dto = PhoneModule::getDtoByPhone($phone);
        if (!$dto) {
            $this->error('Provided phone number is not valid');
        } else {
            $this->info("Phone number +{$dto->getFull()} belongs to country {$dto->getCountryIso()}");
        }
    }
}
