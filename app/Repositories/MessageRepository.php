<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;


class MessageRepository extends BaseRepository
{
    public function getFilters(){
        return [AllowedFilter::exact('user_id'),AllowedFilter::exact('conversion_id')];
    }
    public function getIncludes(){
        return ["users","conversation"];
    }
    public function getSorts(){
        return ["user_id","conversion_id"];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Message::class;
    }
}
