<?php

namespace Qihucms\Role\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Qihucms\Currency\Resources\Type\Type;

class Role extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'times' => $this->times,
            'unit' => $this->unit,
            'is_qualification_pa' => $this->is_qualification_pa,
            'is_qualification_co' => $this->is_qualification_co,
            'price' => $this->price,
            'currency_type' => new Type($this->currency_type),
        ];
    }
}
