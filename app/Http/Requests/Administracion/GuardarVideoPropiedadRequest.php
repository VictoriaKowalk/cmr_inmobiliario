<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class GuardarVideoPropiedadRequest extends FormRequest
{
    protected $errorBag = 'videos';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['nullable', 'string', 'max:150'],
            'youtube_url' => ['required', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'youtube_url.required' => 'Ingresá la URL del video de YouTube.',
            'youtube_url.url' => 'Ingresá una URL válida de YouTube.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'titulo' => trim((string) $this->input('titulo')) ?: null,
            'youtube_url' => trim((string) $this->input('youtube_url')) ?: null,
        ]);
    }
}
