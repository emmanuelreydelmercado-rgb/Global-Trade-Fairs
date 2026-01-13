{!! '<'.'?'.'xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Homepage -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
    </url>
    
    <!-- All Events -->
    @foreach($forms as $form)
    <url>
        <loc>{{ route('fair.details', $form->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
    </url>
    @endforeach
</urlset>
