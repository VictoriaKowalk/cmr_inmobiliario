<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class GuardarImagenPropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'imagenes' => ['required', 'array', 'min:1', 'max:20'],
            'imagenes.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
                'dimensions:max_width=8000,max_height=8000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'imagenes.required' => 'Seleccioná al menos una imagen.',
            'imagenes.max' => 'Podés subir hasta 20 imágenes por vez.',
            'imagenes.*.image' => 'Uno de los archivos no es una imagen válida.',
            'imagenes.*.mimes' => 'Las imágenes deben ser JPG, PNG o WebP.',
            'imagenes.*.max' => 'Cada imagen puede pesar hasta 10 MB.',
            'imagenes.*.dimensions' => 'Cada imagen puede medir como máximo 8000 × 8000 píxeles.',
        ];
    }
}
