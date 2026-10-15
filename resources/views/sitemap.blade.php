{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach(['home', 'menu', 'restaurant', 'hotel', 'contact'] as $page)
@foreach(['fr', 'en'] as $locale)
    <url>
        <loc>{{ route("guest.$locale.$page") }}</loc>
        <xhtml:link rel="alternate" hreflang="fr" href="{{ route("guest.fr.$page") }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ route("guest.en.$page") }}"/>
    </url>
@endforeach
@endforeach
</urlset>
