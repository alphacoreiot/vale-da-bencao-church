{{-- SEO Meta Tags --}}
<meta name="description" content="Igreja Vale da Bênção Church em Camaçari-BA. Cultos aos domingos e quartas, células às quintas. Venha fazer parte da nossa família!">
<meta name="author" content="Igreja Vale da Bênção Church">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="Igreja Vale da Bênção Church - Camaçari BA">
<meta property="og:description" content="Igreja evangélica em Camaçari-BA. Cultos aos domingos 18:30 e quartas 19:00. Células às quintas. Liderança: Apóstolo Ary Dallas e Naele Santana.">
<meta property="og:image" content="{{ asset('assets/logo.png') }}">
<meta property="og:locale" content="pt_BR">
<meta property="og:site_name" content="Igreja Vale da Bênção Church">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Igreja Vale da Bênção Church - Camaçari BA">
<meta name="twitter:description" content="Igreja evangélica em Camaçari-BA. Cultos aos domingos 18:30 e quartas 19:00. Venha fazer parte da nossa família!">
<meta name="twitter:image" content="{{ asset('assets/logo.png') }}">

{{-- Geo Tags (SEO Local) --}}
<meta name="geo.region" content="BR-BA">
<meta name="geo.placename" content="Camaçari">
<meta name="geo.position" content="-12.6957261;-38.2934209">
<meta name="ICBM" content="-12.6957261, -38.2934209">

{{-- Structured Data JSON-LD --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Church",
    "name": "Igreja Vale da Bênção Church",
    "alternateName": "Vale da Bênção Church",
    "description": "Igreja evangélica em Camaçari-BA. Cultos, células, eventos e comunidade de fé.",
    "url": "https://valedabencao.com.br",
    "logo": "https://valedabencao.com.br/assets/logo.png",
    "image": "https://valedabencao.com.br/assets/logo.png",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Rua do Cajueiro - Parque das Palmeiras",
        "addressLocality": "Camaçari",
        "addressRegion": "BA",
        "addressCountry": "BR"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-12.6957261",
        "longitude": "-38.2934209"
    },
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Sunday",
            "opens": "18:30",
            "closes": "20:30"
        },
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Wednesday",
            "opens": "19:00",
            "closes": "21:00"
        },
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Thursday",
            "opens": "19:00",
            "closes": "21:00"
        }
    ],
    "sameAs": [
        "https://www.instagram.com/valedabencaochurch",
        "https://www.youtube.com/@valedabencaochurch"
    ]
}
</script>
