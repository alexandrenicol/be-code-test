<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Organisation;
use App\Mail\OrganisationCreated;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Transformers\OrganisationTransformer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationService $service): JsonResponse
    {
        $this->request->validate([
            'name' => 'required|string'
        ]);

        $this->request->request->add([
            'owner' => Auth::user()
        ]);

        /** @var Organisation $organisation */
        $organisation = $service->createOrganisation($this->request->all());

        Mail::to(Auth::user()->email)->send(new OrganisationCreated($organisation));

        return $this
            ->transformItem('organisation', $organisation, ['user'])
            ->respond();
    }

    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function listAll(OrganisationService $service): JsonResponse
    {
        /** @var Collection $organisations */
        $organisations = $service->listOrganisations($this->request->query());

        return $this
            ->transformCollection('organisations', $organisations, ['user'])
            ->respond();
    }
}
