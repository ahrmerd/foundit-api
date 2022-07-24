<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Repositories\BaseRepository;


class ConversationRepository extends BaseRepository
{
    public function getFilters()
    {
        return [];
    }
    public function getIncludes()
    {
        return ["users", "messages"];
    }
    public function getSorts()
    {
        return [];
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Conversation::class;
    }
}
