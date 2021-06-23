<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * @inheritDoc
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'customer' => $this->resource->customer,
        ]);
    }
}
