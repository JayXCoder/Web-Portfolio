<?php

namespace App\Services\WorkExperience;

use App\Services\WorkExperience\Contracts\DataProcessorInterface;

class DataProcessor implements DataProcessorInterface
{
    /**
     * Process array fields (technologies, achievements, skills_gained)
     */
    public function processArrayFields(array &$data): void
    {
        $arrayFields = ['technologies', 'achievements', 'skills_gained'];
        
        foreach ($arrayFields as $field) {
            if (isset($data[$field])) {
                if (is_string($data[$field])) {
                    // Convert comma-separated string to array
                    $data[$field] = array_map('trim', explode(',', $data[$field]));
                } elseif (!is_array($data[$field])) {
                    $data[$field] = [];
                }
            }
        }
    }

    /**
     * Validate and process work experience data
     */
    public function processWorkExperienceData(array $data): array
    {
        // Process array fields
        $this->processArrayFields($data);

        // Set default values
        $data['is_current'] = $data['is_current'] ?? false;
        $data['is_published'] = $data['is_published'] ?? true;

        return $data;
    }
}
