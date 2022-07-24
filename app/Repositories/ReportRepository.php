<?php

namespace App\Repositories;

use App\Models\Report;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;


class ReportRepository extends BaseRepository
{
    public function getFilters(){
        return ["title",AllowedFilter::exact('user_id'),AllowedFilter::exact('status'),AllowedFilter::exact('type')];
    }
    public function getIncludes(){
        return ["user"];
    }
    public function getSorts(){
        return ["title","status","type"];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Report::class;
    }
}
