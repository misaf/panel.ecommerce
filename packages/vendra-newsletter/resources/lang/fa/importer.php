<?php

declare(strict_types=1);

return [
    'email' => [
        'example'     => 'valid-email@example.com',
        'helper_text' => 'یک آدرس ایمیل معتبر وارد کنید (مثال: user@domain.com).',
    ],
    'newsletter_slug' => [
        'example'     => 'weekly-newsletter,monthly-update',
        'helper_text' => 'شناسه خبرنامه(ها) را با کاما جدا کنید.',
    ],
    'notification' => [
        'completed_body' => 'وارد کردن مشترکین خبرنامه شما تکمیل شد و :successful_count :successful_rows وارد شد.',
        'failed_rows'    => ':failed_count :failed_rows وارد نشد.',
    ],
    'options' => [
        'enable_real_email_validation' => [
            'helper_text' => 'آدرس‌های ایمیل را در برابر سرورهای واقعی ایمیل اعتبارسنجی کنید تا از وجود آنها اطمینان حاصل کنید.',
            'label'       => 'فعال‌سازی اعتبارسنجی واقعی ایمیل',
        ],
        'skip_duplicate_users' => [
            'helper_text' => 'وارد کردن رکوردهایی که کاربر قبلاً مشترک شده است را رد کنید تا از تکرار جلوگیری شود.',
            'label'       => 'رد کردن کاربران تکراری',
        ],
        'update_existing_records' => [
            'helper_text' => 'به جای ایجاد موارد جدید، مشترکین موجود را به‌روزرسانی کنید وقتی موارد تکراری پیدا می‌شود.',
            'label'       => 'به‌روزرسانی رکوردهای موجود',
        ],
    ],
    'user' => [
        'example'     => 'john_doe',
        'helper_text' => 'نام کاربری را برای ارتباط با این مشترک وارد کنید.',
    ],
];
