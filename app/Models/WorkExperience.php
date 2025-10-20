<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class WorkExperience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position',
        'company',
        'company_logo',
        'employment_type',
        'start_date',
        'end_date',
        'is_current',
        'location',
        'description',
        'responsibilities',
        'technologies',
        'achievements',
        'skills_gained',
        'team_size',
        'reporting_to',
        'salary_range',
        'is_published',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_published' => 'boolean',
        'technologies' => 'array',
        'achievements' => 'array',
        'skills_gained' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workExperience) {
            if (empty($workExperience->sort_order)) {
                $workExperience->sort_order = static::max('sort_order') + 1;
            }
        });
    }

    /**
     * Scope to get published work experiences
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get current work experiences
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to get work experiences by employment type
     */
    public function scopeByEmploymentType($query, $type)
    {
        return $query->where('employment_type', $type);
    }

    /**
     * Scope to order work experiences (newest to oldest)
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('start_date', 'desc')->orderBy('sort_order', 'asc');
    }

    /**
     * Get the duration of work experience
     */
    public function getDurationAttribute(): string
    {
        $start = $this->start_date;
        $end = $this->is_current ? now() : $this->end_date;
        
        $totalMonths = $start->diffInMonths($end);
        $years = floor($totalMonths / 12);
        $months = $totalMonths % 12;
        
        if ($years > 0 && $months > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '') . " {$months} month" . ($months > 1 ? 's' : '');
        } elseif ($years > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '');
        } else {
            return "{$months} month" . ($months > 1 ? 's' : '');
        }
    }

    /**
     * Get the employment type with color class
     */
    public function getEmploymentTypeColorAttribute(): string
    {
        return match($this->employment_type) {
            'Full-Time' => 'neon-blue',
            'Part-Time' => 'neon-green',
            'Internship' => 'neon-yellow',
            'Contract' => 'neon-orange',
            'Freelance' => 'neon-purple',
            default => 'neon-gray'
        };
    }

    /**
     * Get the status color class
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_current ? 'neon-green' : 'neon-gray';
    }

    /**
     * Get technologies as comma-separated string
     */
    public function getTechnologiesStringAttribute(): string
    {
        return $this->technologies ? implode(', ', $this->technologies) : '';
    }

    /**
     * Get achievements as comma-separated string
     */
    public function getAchievementsStringAttribute(): string
    {
        return $this->achievements ? implode(', ', $this->achievements) : '';
    }

    /**
     * Get skills gained as comma-separated string
     */
    public function getSkillsGainedStringAttribute(): string
    {
        return $this->skills_gained ? implode(', ', $this->skills_gained) : '';
    }
}
