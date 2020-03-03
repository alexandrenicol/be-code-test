<?php

declare(strict_types=1);

namespace App\Services;

use App\Organisation;
use Carbon\Carbon;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        $organisation = new Organisation();
    
        var_dump($attributes);

        $organisation->name = $attributes['name'];
        $organisation->owner_user_id = $attributes['owner_user_id'];
        $organisation->trial_end = Carbon::now()->addDays(30);
        $organisation->subscribed = false;
        $organisation->save();

        return $organisation;
    }
}
