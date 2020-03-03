<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Organisation;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class OrganisationTransformer
 * @package App\Transformers
 */
class OrganisationTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function transform(Organisation $organisation): array
    {

        // not entirely sure why, but trial_end comes back as a string when returning the collection,
        // and as an object when creating a new organisation
        if (gettype($organisation->trial_end) == gettype("")) {
            $trialEnd = new Carbon($organisation->trial_end);
            $organisation->trial_end = $trialEnd->toDateTime();
        } 

        return [
            'id' => (int) $organisation->id,
            'name' => $organisation->name,
            'trial_end' => ($organisation->subscribed ? null : $organisation->trial_end->getTimestamp()),
            'subscribed' => $organisation->subscribed,
            'created_at' => $organisation->created_at->getTimestamp(),
            'updated_at' => $organisation->updated_at->getTimestamp()
        ];
    }

    /**
     * @param Organisation $organisation
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Organisation $organisation)
    {
        return $this->item($organisation->owner, new UserTransformer);
    }
}
