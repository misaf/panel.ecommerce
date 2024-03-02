<a wire:click="$set('faqCategory', '{{ $faqCategory->slug }}')" @class([
    'block hover:underline',
    // 'text-gray-500 dark:text-yellow-400' => $loop->first,
    // 'text-gray-500 dark:text-gray-300' => !$loop->first,
]) href="#">{{ $faqCategory->name }}</a>
