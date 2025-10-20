<?php

namespace App\Services\WorkExperience\Contracts;

interface DataProcessorInterface
{
    public function processArrayFields(array &$data): void;
    public function processWorkExperienceData(array $data): array;
}
