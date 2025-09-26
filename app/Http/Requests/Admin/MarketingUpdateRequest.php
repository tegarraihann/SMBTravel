<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarketingUpdateRequest extends FormRequest
{
  public function authorize(): bool { return true; }

  public function rules(): array {
    $id = $this->route('marketing'); // Route model binding id
    return [
      'name'   => ['required','string','max:150'],
      'phone'  => ['nullable','string','max:30'],
      'email'  => ['nullable','email','max:150', Rule::unique('marketings','email')->ignore($id)],
      'status' => ['required','in:active,inactive'],
      'notes'  => ['nullable','string','max:2000'],

      'agents'                         => ['array'],
      'agents.*.id'                    => ['nullable','integer'],
      'agents.*._delete'               => ['sometimes','boolean'], // untuk hapus baris
      'agents.*.name'                  => ['required','string','max:150'],
      'agents.*.phone'                 => ['nullable','string','max:30'],
      'agents.*.email'                 => ['nullable','email','max:150'],
      'agents.*.commission_rate'       => ['nullable','numeric','min:0','max:100'],
      'agents.*.status'                => ['required','in:active,inactive'],
      'agents.*.notes'                 => ['nullable','string','max:1000'],
    ];
  }
}
