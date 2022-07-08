<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;


class LocationRepository extends BaseRepository
{
    public function getFilters()
    {
        return [AllowedFilter::exact("state_id"), "name"];
    }
    public function getIncludes()
    {
        return ["state"];
    }
    public function getSorts()
    {
        return ["name"];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Location::class;
    }
}
