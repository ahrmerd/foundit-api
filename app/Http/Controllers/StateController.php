<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Models\State;
use App\Repositories\StateRepository;

class StateController extends Controller
{

    private $stateRepository;

    public function __construct(StateRepository $stateRepo)
    {
        $this->stateRepository = $stateRepo;
    }

    public function index()
    {
        return $this->stateRepository->index();
    }


    public function show($id)
    {
        return $this->stateRepository->getById($id);
    }
}
