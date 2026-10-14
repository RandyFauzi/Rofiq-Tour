<?php

namespace App\Http\Requests;

use App\Admin\ContentRegistry;
use App\Models\Hotel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AdminContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        foreach (ContentRegistry::get($this->route('resource'))['fields'] as $name => $field) {
            if ($field['type'] === 'checkbox') {
                $this->merge([$name => $this->boolean($name)]);
            }
        }
    }

    public function rules(): array
    {
        $key = $this->route('resource');
        $definition = ContentRegistry::get($key);
        $table = (new $definition['model'])->getTable();
        $rules = [];
        foreach ($definition['fields'] as $name => $field) {
            if ($field['type'] === 'image') {
                $rules[$name] = [in_array($key, ['gallery', 'banners']) && ! $this->route('record') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:max_width=6000,max_height=6000'];
            } else {
                $rules[$name] = explode('|', $field['rules']);
                if ($field['unique'] ?? false) {
                    $rules[$name][] = Rule::unique($table, $name)->ignore($this->route('record'));
                }
            }
        }
        if ($key === 'rates') {
            $rules['room_type'][] = Rule::unique('departure_rates', 'room_type')->where('departure_id', $this->input('departure_id'))->where('tier', $this->input('tier'))->ignore($this->route('record'));
        }
        if ($key === 'departures') {
            $rules['departure_date'][] = Rule::unique('departures', 'departure_date')->where('package_id', $this->input('package_id'))->where('airline_id', $this->input('airline_id'))->where('duration_days', $this->input('duration_days'))->ignore($this->route('record'));
        }
        if ($key === 'hotels') {
            $rules['name'][] = Rule::unique('hotels', 'name')->where('city', $this->input('city'))->ignore($this->route('record'));
        }

        return $rules;
    }

    public function attributes(): array
    {
        return collect(ContentRegistry::get($this->route('resource'))['fields'])->mapWithKeys(fn ($f, $k) => [$k => $f['label']])->all();
    }

    public function after(): array
    {
        return [function (Validator $v) {
            if ($v->errors()->any()) {
                return;
            }
            if ($this->route('resource') === 'rates') {
                foreach (['makkah_hotel_id' => 'Makkah', 'madinah_hotel_id' => 'Madinah'] as $field => $city) {
                    if (Hotel::find($this->integer($field))?->city !== $city) {
                        $v->errors()->add($field, "Pilih hotel di {$city}.");
                    }
                }
            }
            if ($this->route('resource') === 'packages') {
                foreach (['included', 'excluded', 'itinerary'] as $field) {
                    foreach (preg_split('/\\r?\\n/', $this->input($field) ?? '') as $line) {
                        if (mb_strlen($line) > 255 && $field !== 'itinerary') {
                            $v->errors()->add($field, 'Maksimal 255 karakter per fasilitas.');
                        }
                        if ($field === 'itinerary' && trim($line) !== '') {
                            $parts = explode('|', $line, 2);
                            if (count($parts) !== 2 || trim($parts[0]) === '' || mb_strlen(trim($parts[0])) > 150 || trim($parts[1]) === '') {
                                $v->errors()->add($field, 'Gunakan format Judul | Keterangan, judul maksimal 150 karakter.');
                            }
                        }
                    }
                }
            }
        }];
    }
}
