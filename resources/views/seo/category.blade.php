@php
    /** @var \App\Models\Category $category */
    /** @var array{heading: string, intro: string, intro_secondary: string, rooms_heading: string, faq: list<array{question: string, answer: string}>} $content */
    /** @var list<array<string, mixed>> $rooms */
@endphp
<article id="seo-landing-server" class="seo-landing border-t border-neutral-800 bg-neutral-950 text-white">
    <div class="mx-auto max-w-6xl space-y-8 px-4 py-8 sm:py-10">
        <header class="space-y-4">
            <nav class="text-sm text-neutral-500" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2">
                    <li>
                        <a href="{{ route('home') }}" class="transition-colors hover:text-yellow-400">{{ __('Home') }}</a>
                    </li>
                    <li aria-hidden="true">/</li>
                    <li class="text-neutral-300">{{ __($category->name) }}</li>
                </ol>
            </nav>

            <div class="space-y-3">
                <h1 class="text-3xl font-extrabold text-white sm:text-4xl">{{ $content['heading'] }}</h1>
                <p class="max-w-3xl text-base leading-relaxed text-neutral-300 sm:text-lg">{{ $content['intro'] }}</p>
                <p class="max-w-3xl text-sm leading-relaxed text-neutral-400 sm:text-base">{{ $content['intro_secondary'] }}</p>
            </div>
        </header>

        <section aria-labelledby="seo-category-rooms-heading">
            <h2 id="seo-category-rooms-heading" class="mb-4 text-xl font-bold text-white sm:text-2xl">
                {{ $content['rooms_heading'] }}
            </h2>
            <ul class="grid grid-cols-2 gap-2 sm:gap-3 md:grid-cols-3 xl:grid-cols-4">
                @foreach ($rooms as $room)
                    <li>
                        <a href="{{ route('rooms.show', $room['slug']) }}" class="block rounded-lg border border-neutral-800 bg-neutral-900/80 px-3 py-2 text-sm font-medium text-neutral-200 transition-colors hover:border-neutral-700 hover:text-white">
                            {{ $room['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="rounded-2xl border border-neutral-800 bg-neutral-900/50 p-5 sm:p-6" aria-labelledby="seo-category-faq-heading">
            <h2 id="seo-category-faq-heading" class="mb-4 text-xl font-bold text-white">
                {{ __('Category page FAQ title', ['category' => __($category->name)]) }}
            </h2>
            <dl class="space-y-4">
                @foreach ($content['faq'] as $item)
                    <div class="border-b border-neutral-800 pb-4 last:border-b-0 last:pb-0">
                        <dt class="font-semibold text-white">{{ $item['question'] }}</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-neutral-400 sm:text-base">{{ $item['answer'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <p>
            <a href="{{ route('rankings.index') }}" class="text-yellow-400 underline-offset-2 hover:underline">
                {{ __('View rankings') }}
            </a>
        </p>
    </div>
</article>
