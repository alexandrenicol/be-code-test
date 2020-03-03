<?php

declare(strict_types=1);

namespace App\Services;

use App\Organisation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
        /** @var Organisation $organisation */
        $organisation = new Organisation();

        $organisation->name = $attributes['name'];
        $organisation->owner()->associate($attributes['owner']);
        $organisation->trial_end = Carbon::now()->addDays(30);
        $organisation->subscribed = false;
        $organisation->save();

        return $organisation;
    }

    /**
     * @param array $attributes
     * 
     * @return Collection
     */
    public function listOrganisations(array $attributes): Collection 
    {
        /** @var string $filter */
        $filter = array_key_exists('filter', $attributes) ? $attributes['filter'] : 'all';

        /** @var Collection $organisation */
        switch ($filter) {
            case 'subbed':
                $organisations = Organisation::where('subscribed', true)->get();
                break;
            case 'trial':
                $organisations = Organisation::where('subscribed', false)->get();
                // not handling the case where trial is expired though.
                break;
            case 'all':
            default:
                $organisations = Organisation::all();
                break;
        }

        return $organisations;
    }
}
