@props([
    'isError' => false,
])

<input {{ $attributes
            ->class([
                'w-full h-14 px-4 rounded-lg border border-[#A07BF0] bg-white/20 focus:border-pink focus:shadow-[0_0_0_2px_#EC4176] outline-none transition text-white placeholder:text-white text-xxs md:text-xs font-semibold',
                '_is-error' => $isError
            ])
       }}
>

