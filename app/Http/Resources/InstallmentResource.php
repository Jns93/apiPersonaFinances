<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
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
            'expense_id' => $this->expense_id,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'fl_pay' => $this->fl_pay
        ];
    }
}
