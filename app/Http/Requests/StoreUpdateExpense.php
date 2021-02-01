<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateExpense extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatorio',
            'category_id.required' => 'O campo categoria é obrigatorio',
            'subcategory_id.required' => 'O campo subcategoria é obrigatorio',
            'amount.required' => 'O campo valor é obrigatorio',
            'installments' => 'Informe o numero de parcelas',
            'due_date' => 'Informe a data de vencimento'
        ];
    }
}
