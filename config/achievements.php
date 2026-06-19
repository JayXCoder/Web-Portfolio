<?php

/**
 * Site-wide achievements settings and type definitions.
 * Items are managed via the admin panel (achievements table).
 */
return [
    'credly_profile' => 'https://www.credly.com/users/jawahar-ganesh/badges/credly',

    'types' => [
        'certificate' => [
            'label' => 'Certificate',
            'organization_label' => 'Issuing organization',
            'date_label' => 'Issued',
            'story_label' => 'What I did',
            'project_label' => 'Related project',
            'cover_label' => 'Certificate / badge image',
            'photo_label' => 'Receiving photo',
            'show_credly' => true,
        ],
        'award' => [
            'label' => 'Award',
            'organization_label' => 'Presented by',
            'date_label' => 'Received',
            'story_label' => 'The story',
            'project_label' => 'Related work',
            'cover_label' => 'Award / trophy image',
            'photo_label' => 'Ceremony photo',
            'show_credly' => false,
        ],
        'competition' => [
            'label' => 'Competition',
            'organization_label' => 'Event / organizer',
            'date_label' => 'Won',
            'story_label' => 'How it happened',
            'project_label' => 'Project or entry',
            'cover_label' => 'Competition image',
            'photo_label' => 'Competition highlight',
            'show_credly' => false,
        ],
        'stage' => [
            'label' => 'Stage win',
            'organization_label' => 'Event / platform',
            'date_label' => 'Date',
            'story_label' => 'The moment',
            'project_label' => 'Performance / project',
            'cover_label' => 'Cover image',
            'photo_label' => 'On-stage photo',
            'show_credly' => false,
        ],
    ],
];
