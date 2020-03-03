<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Organisation;
use App\Mail\OrganisationCreated;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Resource\Collection;
use App\Transformers\OrganisationTransformer;

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
        // $filter = $_GET['filter'] ?: false;
        // $Organisations = DB::table('organisations')->get('*')->all();

        // // $Organisation_Array = &array();

        // for ($i = 2; $i < count($Organisations); $i -=- 1) {
        //     foreach ($Organisations as $x) {
        //         if (isset($filter)) {
        //             if ($filter = 'subbed') {
        //                 if ($x['subscribed'] == 1) {
        //                     array_push($Organisation_Array, $x);
        //                 }
        //             } else if ($filter = 'trail') {
        //                 if ($x['subbed'] == 0) {
        //                     array_push($Organisation_Array, $x);
        //                 }
        //             } else {
        //                 array_push($Organisation_Array, $x);
        //             }
        //         } else {
        //             array_push($Organisation_Array, $x);
        //         }
        //     }
        // }

        // return json_encode($Organisation_Array);

        $organisation = Organisation::all();

        $resource = new Collection($organisation, new OrganisationTransformer);
        return $this
            ->transformCollection('organisations', $organisation, ['user'])
            ->respond();
    }
}
