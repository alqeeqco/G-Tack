<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GovernorateCollection extends ResourceCollection
{
    public $collects = GovernorateResource::class;

    // public function toArray($request)
    // {
        // return [
        //     'code' => 200,
        //     'status' => true,
        //     'data' => $this->collection,
        // ];
    // }
}
