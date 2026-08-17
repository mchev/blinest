@php
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$baseUrl = rtrim(config('app.url'), '/');
$now = now()->toAtomString();
$docPaths = [
    ['path' => '/docs', 'priority' => '0.60'],
    ['path' => '/docs/howto', 'priority' => '0.65'],
    ['path' => '/docs/glossary', 'priority' => '0.55'],
    ['path' => '/docs/create-content', 'priority' => '0.55'],
    ['path' => '/docs/faq', 'priority' => '0.50'],
    ['path' => '/docs/level', 'priority' => '0.45'],
    ['path' => '/docs/elo', 'priority' => '0.45'],
    ['path' => '/contact', 'priority' => '0.25'],
];
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  @foreach($locales as $locale)
  <url>
    <loc>{{ \App\Seo\LocaleUrl::localizedPath('', $locale) }}</loc>
    <lastmod>{{ $now }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.00</priority>
  </url>
  @endforeach

  @foreach($publicRooms as $room)
  <url>
    <loc>{{ $room->url }}</loc>
    <lastmod>{{ $room->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.90</priority>
  </url>
  @endforeach

  @foreach($pages as $page)
    @foreach($page->urls as $url)
  <url>
    <loc>{{ $url }}</loc>
    <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
    <changefreq>yearly</changefreq>
    <priority>0.30</priority>
  </url>
    @endforeach
  @endforeach

  @foreach($locales as $locale)
    @foreach($docPaths as $doc)
  <url>
    <loc>{{ \App\Seo\LocaleUrl::localizedPath(ltrim($doc['path'], '/'), $locale) }}</loc>
    <lastmod>{{ $now }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>{{ $doc['priority'] }}</priority>
  </url>
    @endforeach
  @endforeach

</urlset>
