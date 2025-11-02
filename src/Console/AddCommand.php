<?php

namespace App\Modules\Users\Console;

use App\Helpers\PhoneHelper;
use App\Modules\Users\Actions\UserCreateAction;
use App\Modules\Users\Enums\UserRoleEnum;
use App\Modules\Users\Exceptions\AlreadyExistsPhoneException;
use App\Modules\Users\Requests\UserCreateRequestDto;
use Illuminate\Console\Command;

class AddCommand extends Command
{
    protected $signature = 'users:add {--role=} {--phone=} {--password=} ';

    protected $description = 'Add new user. Provide --role=, --phone= and --password=';

    public function handle(): void
    {
        if (app()->environment('production')) {
            $this->error('This action is prohibited in production');
            return;
        }

        // Detect role
        $oRole = $this->option('role');
        $role = $oRole && UserRoleEnum::tryFrom(strtoupper($oRole))
            ? UserRoleEnum::from(strtoupper($oRole))
            : UserRoleEnum::USER;

        // Detect phone
        $phone = PhoneHelper::clean($this->option('phone'));
        if (!$phone) {
            $this->error('Provide valid phone by option --phone=');
            return;
        }

        try {
            $action = (new UserCreateAction(
                (new UserCreateRequestDto)
                    ->setRole($role)
                    ->setPhone($phone)
                    ->setPassword($this->option('password'))
            ));

            $user = $action->execute();

            $this->info("User created. ID: $user->id. Phone: +$user->phone. Password: {$action->getPassword()}");

        } catch (AlreadyExistsPhoneException) {
            $this->error('Provided phone already exists');
        } catch (\Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
