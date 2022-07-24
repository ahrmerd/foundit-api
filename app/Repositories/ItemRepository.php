<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;


class ItemRepository extends BaseRepository
{
    public function getFilters()
    {
        return [AllowedFilter::exact('category_id'), AllowedFilter::exact('user_id'), AllowedFilter::exact('location_id'),  "name"];
    }
    public function getIncludes()
    {
        return ["category", "user", "location"];
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
        return Item::class;
    }
}
