<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'name' => $this->name,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'fl_pay' => $this->fl_pay,
            'description' => $this->description,
            'fl_fixed' => $this->fl_fixed
        ];
    }
}
