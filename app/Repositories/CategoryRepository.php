<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;


class CategoryRepository extends BaseRepository
{
    public function getFilters(){
        return ["name"];
    }
    public function getIncludes(){
        return ["items"];
    }
    public function getSorts(){
        return ["name"];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Category::class;
    }
}
