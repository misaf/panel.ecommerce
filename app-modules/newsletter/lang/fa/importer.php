<?php

declare(strict_types=1);

return [
    'newsletter_slug' => [
        'helper_text'    => 'شناسه خبرنامه(ها) را با کاما جدا کنید.',
        'example'        => 'weekly-newsletter,monthly-update',
    ],
    'user' => [
        'helper_text'    => 'نام کاربری را برای ارتباط با این مشترک وارد کنید.',
        'example'        => 'john_doe',
    ],
    'email' => [
        'helper_text'    => 'یک آدرس ایمیل معتبر وارد کنید (مثال: user@domain.com).',
        'example'        => 'valid-email@example.com',
    ],
    'options' => [
        'enable_real_email_validation' => [
            'label'       => 'فعال‌سازی اعتبارسنجی واقعی ایمیل',
            'helper_text' => 'آدرس‌های ایمیل را در برابر سرورهای واقعی ایمیل اعتبارسنجی کنید تا از وجود آنها اطمینان حاصل کنید.',
        ],
        'update_existing_records' => [
            'label'       => 'به‌روزرسانی رکوردهای موجود',
            'helper_text' => 'به جای ایجاد موارد جدید، مشترکین موجود را به‌روزرسانی کنید وقتی موارد تکراری پیدا می‌شود.',
        ],
        'skip_duplicate_users' => [
            'label'       => 'رد کردن کاربران تکراری',
            'helper_text' => 'وارد کردن رکوردهایی که کاربر قبلاً مشترک شده است را رد کنید تا از تکرار جلوگیری شود.',
        ],
    ],
    'notification' => [
        'completed_body' => 'وارد کردن مشترکین خبرنامه شما تکمیل شد و :successful_count :successful_rows وارد شد.',
        'failed_rows'    => ':failed_count :failed_rows وارد نشد.',
    ],
];
