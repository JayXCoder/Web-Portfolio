<?php

namespace App\Http\Requests;

use App\Support\CommentSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'author_name' => CommentSanitizer::authorName((string) $this->input('author_name', '')),
            'author_email' => CommentSanitizer::email($this->input('author_email')),
            'body' => CommentSanitizer::body((string) $this->input('body', '')),
            // Honeypot — never trust client-facing "website" as real data.
            'website' => is_string($this->input('website')) ? trim($this->input('website')) : '',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'min:2', 'max:120'],
            'author_email' => ['nullable', 'email:filter', 'max:255'],
            'body' => ['required', 'string', 'min:2', 'max:5000'],
            // Honeypot is checked in the controller (silent discard), not via failing rules.
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'author_name.required' => 'Please enter your name.',
            'body.required' => 'Please enter a comment.',
        ];
    }

    public function isHoneypotTriggered(): bool
    {
        return filled($this->input('website'));
    }
}
