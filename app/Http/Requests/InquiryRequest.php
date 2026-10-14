<?php

namespace App\Http\Requests;

use App\Models\Departure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'regex:/^(\\+?62|0)8[0-9]{7,12}$/'],
            'city' => ['nullable', 'string', 'max:100'],
            'travelers' => ['required', 'integer', 'min:1', 'max:100'],
            'package_id' => ['nullable', 'integer', Rule::exists('packages', 'id')->where('is_published', true)->whereNull('deleted_at')],
            'departure_id' => ['nullable', 'integer', Rule::exists('departures', 'id')->whereNull('deleted_at')],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            if (! $this->filled('departure_id') || $validator->errors()->any()) {
                return;
            }
            $departure = Departure::publiclyVisible()->find($this->integer('departure_id'));
            if (! $departure || $departure->package_id !== $this->integer('package_id')) {
                $validator->errors()->add('departure_id', 'Pilih jadwal aktif yang sesuai dengan paket.');
            }
        }];
    }

    public function messages(): array
    {
        return ['phone.regex' => 'Gunakan nomor WhatsApp Indonesia yang valid.', 'consent.accepted' => 'Persetujuan diperlukan untuk menghubungi Anda.'];
    }
}
