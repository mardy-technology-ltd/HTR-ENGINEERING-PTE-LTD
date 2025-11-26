<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{7,20}$/', 'max:20'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'business_hours' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'whatsapp' => ['required', 'string', 'regex:/^[0-9]{7,15}$/', 'max:20'],
            'facebook_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?facebook\.com/', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?instagram\.com/', 'max:255'],
            'whatsapp_url' => ['nullable', 'url', 'regex:/^https?:\/\/(wa\.me|whatsapp\.com)/', 'max:255'],
            'footer_tagline' => ['nullable', 'string', 'max:500'],
            'footer_services' => ['nullable', 'array', 'max:5'],
            'footer_services.*' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'whatsapp.regex' => 'Please enter a valid WhatsApp number (digits only).',
            'facebook_url.regex' => 'Please enter a valid Facebook URL.',
            'instagram_url.regex' => 'Please enter a valid Instagram URL.',
            'whatsapp_url.regex' => 'Please enter a valid WhatsApp link.',
            'footer_tagline.max' => 'Footer tagline cannot exceed 500 characters.',
            'footer_services.max' => 'You can add maximum 5 footer services.',
            'footer_services.*.required' => 'Each service name is required.',
            'footer_services.*.max' => 'Service name cannot exceed 100 characters.',
        ];
    }
}
