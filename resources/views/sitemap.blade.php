<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <url>
    <loc>https://blinest.com/</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>1.00</priority>
  </url>

  @foreach($publicRooms as $key => $room)
  <url>
    <loc>{{ $room->url }}</loc>
    <lastmod>{{ $room->updated_at->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>{{ 0.90 - ($key / 100) }}</priority>
  </url>
  @endforeach

  @foreach($pages as $page)
  <url>
    <loc>{{ $page->url }}</loc>
    <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
    <changefreq>yearly</changefreq>
    <priority>0.10</priority>
  </url>
  @endforeach

  <url>
    <loc>https://blinest.com/login</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.20</priority>
  </url>

  <url>
    <loc>https://blinest.com/register</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.20</priority>
  </url>

  <url>
    <loc>https://blinest.com/contact</loc>
    <lastmod>2019-10-02T15:27:50+00:00</lastmod>
    <priority>0.20</priority>
  </url>

</urlset>