<?php

namespace App\Repositories;

use App\Models\State;
use App\Repositories\BaseRepository;


class StateRepository extends BaseRepository
{
    public function getFilters(){
        return ["name"];
    }
    public function getIncludes(){
        return ["locations"];
    }
    public function getSorts(){
        return ["id","created_at","name"];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return State::class;
    }
}
