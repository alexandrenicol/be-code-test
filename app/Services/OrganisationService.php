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

        $organisation->name = $attributes['name'];
        $organisation->owner()->associate($attributes['owner']);
        $organisation->trial_end = Carbon::now()->addDays(30);
        $organisation->subscribed = false;
        $organisation->save();

        return $organisation;
    }
}
