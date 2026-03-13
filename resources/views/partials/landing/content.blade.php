{{-- ============================================================
     resources/views/partials/landing/content.blade.php
     
     File induk — hanya berisi @include per section.
     Edit masing-masing section di folder sections/ agar tidak
     campur aduk satu sama lain.
     ============================================================ --}}

@include('partials.landing.sections.video')

@include('partials.landing.sections.fitur')

@include('partials.landing.sections.statistik')

@include('partials.landing.sections.cara-kerja')

@include('partials.landing.sections.testimoni')

{{-- ============================================================
     GLOBAL SCROLL OBSERVER
     Dipasang sekali di sini agar berlaku untuk semua section.
     ============================================================ --}}
<script>
(function () {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.nz-reveal,.nz-reveal-left,.nz-reveal-right,.nz-reveal-scale')
            .forEach(function(el){ el.classList.add('nz-visible'); });
        return;
    }
    var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('nz-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.nz-reveal,.nz-reveal-left,.nz-reveal-right,.nz-reveal-scale')
        .forEach(function(el){ obs.observe(el); });
})();
</script>