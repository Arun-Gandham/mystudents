<?php

return [
    [
        'label' => 'Dashboard',
        'icon'  => 'bi bi-speedometer2',
        'route' => 'tenant.dashboard',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Roles',
        'icon'  => 'bi bi-shield-lock',
        'route' => 'tenant.roles.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Permissions',
        'icon'  => 'bi bi-key',
        'route' => 'tenant.permissions.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Academic Years',
        'icon'  => 'bi bi-calendar2-range',
        'route' => 'tenant.academic_years.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Grades',
        'icon'  => 'bi bi-diagram-2',
        'route' => 'tenant.grades.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Sections',
        'icon'  => 'bi bi-grid-3x3-gap',
        'route' => 'tenant.sections.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Timetables',
        'icon'  => 'bi bi-calendar3-week',
        'route' => 'tenant.timetables.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Holidays',
        'icon'  => 'bi bi-calendar-event',
        'route' => 'tenant.school_holidays.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Calendar',
        'icon'  => 'bi bi-calendar4-week',
        'route' => 'tenant.calendar.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Subjects',
        'icon'  => 'bi bi-journal-bookmark',
        'route' => 'tenant.subjects.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Staff',
        'icon'  => 'bi bi-people',
        'route' => 'tenant.staff.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Student Applications',
        'icon'  => 'bi bi-file-earmark-text',
        'route' => 'tenant.applications.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Student Admissions',
        'icon'  => 'bi bi-person-check',
        'route' => 'tenant.admissions.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Students',
        'icon'  => 'bi bi-person-lines-fill',
        'route' => 'tenant.students.index',
        'permissions' => [],
        'roles' => [],
    ],

    [
        'label' => 'Attendance',
        'icon'  => 'bi bi-clipboard-check',
        'permissions' => [],
        'roles' => [],
        'children' => [
            [
                'label' => 'Staff Attendance',
                'icon'  => 'bi bi-clock-history',
                'route' => 'tenant.staffAttendance.list',
                'permissions' => [],
                'roles' => [],
            ],
            [
                'label' => 'Student Attendance',
                'icon'  => 'bi bi-clipboard-data',
                'route' => 'tenant.studentAttendance.index',
                'permissions' => [],
                'roles' => [],
            ],
        ],
    ],

    [
        'label' => 'Exams',
        'icon'  => 'bi bi-journal-check',
        'permissions' => [],
        'roles' => [],
        'children' => [
            [
                'label' => 'Manage Exams',
                'icon'  => 'bi bi-clipboard-check',
                'route' => 'tenant.exams.index',
                'permissions' => [],
                'roles' => [],
            ],
            [
                'label' => 'Exam Results',
                'icon'  => 'bi bi-clipboard-data',
                'route' => '#',
                'permissions' => [],
                'roles' => [],
            ],
        ],
    ],

    [
        'label' => 'Fees',
        'icon'  => 'bi bi-cash-coin',
        'permissions' => [],
        'roles' => [],
        'children' => [
            [
                'label' => 'Fee Heads',
                'icon'  => 'bi bi-tags',
                'route' => 'tenant.fees.fee-heads.index',
                'permissions' => [],
                'roles' => [],
            ],
            [
                'label' => 'Section Fees',
                'icon'  => 'bi bi-collection',
                'route' => 'tenant.fees.section-fees.index',
                'permissions' => [],
                'roles' => [],
            ],
            [
                'label' => 'Student Fee Items',
                'icon'  => 'bi bi-list-check',
                'route' => '#',
                'permissions' => [],
                'roles' => [],
            ],
            [
                'label' => 'Receipts / Payments',
                'icon'  => 'bi bi-receipt',
                'route' => 'tenant.fees.fee-receipts.all',
                'permissions' => [],
                'roles' => [],
            ],
        ],
    ],

    [
        'label' => 'System Settings',
        'icon'  => 'bi bi-gear',
        'route' => '#',
        'permissions' => [],
        'roles' => [],
    ],
];
