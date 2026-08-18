@php
    /** @var \App\Models\Room $room */
    /** @var array{content: array, breadcrumbs: list<array{label: string, href: string|null}>, similar_rooms: list<array<string, mixed>>, stats: array} $seo */
@endphp
<article id="seo-landing-server" class="seo-landing border-t border-white/10 bg-brand-midnight text-white">
    <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 md:py-10">
        <nav class="text-sm text-white/50" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-2">
                @foreach ($seo['breadcrumbs'] as $index => $crumb)
                    <li class="flex items-center gap-2">
                        @if ($crumb['href'])
                            <a href="{{ $crumb['href'] }}" class="transition-colors hover:text-brand-secondary">{{ $crumb['label'] }}</a>
                        @else
                            <span class="text-white/80">{{ $crumb['label'] }}</span>
                        @endif
                        @if ($index < count($seo['breadcrumbs']) - 1)
                            <span aria-hidden="true">/</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

        <header class="space-y-3">
            <h1 class="text-2xl font-extrabold text-white md:text-3xl">{{ $room->name }}</h1>
            <p class="text-base leading-relaxed text-white/80 md:text-lg">{{ $seo['content']['intro'] }}</p>
            <p class="text-sm leading-relaxed text-white/60 md:text-base">{{ $seo['content']['intro_secondary'] }}</p>
        </header>

        <dl class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-brand-deep px-3 py-2.5">
                <dt class="text-xs uppercase tracking-wide text-white/50">{{ __('Tracks') }}</dt>
                <dd class="text-lg font-bold text-white">{{ $seo['stats']['tracks'] }}</dd>
            </div>
            <div class="rounded-lg border border-white/10 bg-brand-deep px-3 py-2.5">
                <dt class="text-xs uppercase tracking-wide text-white/50">{{ __('Rounds played') }}</dt>
                <dd class="text-lg font-bold text-white">{{ $seo['stats']['rounds'] }}</dd>
            </div>
            <div class="col-span-2 rounded-lg border border-white/10 bg-brand-deep px-3 py-2.5 sm:col-span-1">
                <dt class="text-xs uppercase tracking-wide text-white/50">{{ __('Players online') }}</dt>
                <dd class="text-lg font-bold text-white">{{ $seo['stats']['players_online'] }}</dd>
            </div>
        </dl>

        @if (! empty($seo['similar_rooms']))
            <section aria-labelledby="seo-similar-rooms-heading">
                <h2 id="seo-similar-rooms-heading" class="mb-3 text-sm font-medium uppercase tracking-wider text-brand-secondary">
                    {{ __('Similar official rooms') }}
                </h2>
                <ul class="grid grid-cols-2 gap-2 md:grid-cols-4">
                    @foreach ($seo['similar_rooms'] as $similar)
                        <li>
                            <a href="{{ route('rooms.show', $similar['slug']) }}" class="block rounded-lg border border-white/10 bg-brand-deep px-3 py-2 text-sm font-medium text-white/80 transition-colors hover:border-white/20 hover:text-white">
                                {{ $similar['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="rounded-xl border border-white/10 bg-brand-deep/60 p-4 md:p-5" aria-labelledby="seo-room-faq-heading">
            <h2 id="seo-room-faq-heading" class="mb-3 text-lg font-bold text-white">
                {{ __('Room page FAQ title', ['room' => $room->name]) }}
            </h2>
            <dl class="space-y-3">
                @foreach ($seo['content']['faq'] as $item)
                    <div>
                        <dt class="font-semibold text-white">{{ $item['question'] }}</dt>
                        <dd class="mt-1 text-sm leading-relaxed text-white/60">{{ $item['answer'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <p>
            <a href="{{ route('rankings.index') }}" class="text-brand-secondary underline-offset-2 hover:underline">
                {{ __('View rankings') }}
            </a>
        </p>
    </div>
</article>
