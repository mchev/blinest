<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <url>
    <loc>https://blinest.com/</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>1.00</priority>
  </url>

  <url>
    <loc>https://blinest.com/releases</loc>
    <lastmod>2019-11-23T10:27:50+00:00</lastmod>
    <priority>0.90</priority>
  </url>

  @foreach($categories as $page)
  <url>
    <loc>https://blinest.com/parties/{{ $page->slug }}</loc>
    <lastmod>{{ $page->updated_at->tz('UTC')->toAtomString() }}</lastmod>
    <priority>0.90</priority>
  </url>
  @endforeach

  <url>
    <loc>https://blinest.com/login</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

  <url>
    <loc>https://blinest.com/register</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

  <url>
    <loc>https://blinest.com/contact</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

  <url>
    <loc>https://blinest.com/mentions-legales</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

  <url>
    <loc>https://blinest.com/politique-confidentialite</loc>
    <lastmod>2019-10-31T09:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

  <url>
    <loc>https://blinest.com/password/reset</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.10</priority>
  </url>

</urlset>