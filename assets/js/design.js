/* ==========================================================================
   Moghadam — design layer behaviour
   Deps (loaded before this file): gsap, ScrollTrigger, Lenis
   Settings come from wp_localize_script as window.moghadamDesign.

   Motion budget: transforms, opacity and clip-path only — no filters, no
   layout-affecting properties, every scroll reveal runs once.
   ========================================================================== */
(function () {
  'use strict';

  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  // Without the CDN deps the page stays a plain, scrollable document.
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof Lenis === 'undefined') {
    document.documentElement.classList.remove('js', 'lock');
    return;
  }

  var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var CFG = window.moghadamDesign || {};
  var CLOCK = CFG.clock || {};

  gsap.registerPlugin(ScrollTrigger);

  /* ------------------------------------------------------------------ *
   * 0. Smooth scroll (Lenis) wired into ScrollTrigger
   * ------------------------------------------------------------------ */
  var lenis = new Lenis({
    duration: 1.1,
    easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
    smoothWheel: !REDUCED
  });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
  gsap.ticker.lagSmoothing(0);

  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
  window.scrollTo(0, 0);

  // handy handle for debugging / for the WordPress port
  window.MPRO = { lenis: lenis };

  /* ------------------------------------------------------------------ *
   * 1. Theme toggle
   * ------------------------------------------------------------------ */
  $$('[data-theme-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      try { localStorage.setItem('mpro-theme', next); } catch (e) {}
    });
  });

  /* ------------------------------------------------------------------ *
   * 2. Live Istanbul clock
   * ------------------------------------------------------------------ */
  (function clock() {
    var nodes = $$('[data-clock]');
    if (!nodes.length) return;
    var fmt;
    try {
      fmt = new Intl.DateTimeFormat('en-GB', {
        timeZone: CLOCK.timeZone || 'Europe/Istanbul',
        hour: '2-digit', minute: '2-digit', hour12: false
      });
    } catch (e) { fmt = null; }
    function tick() {
      var t = fmt ? fmt.format(new Date()) : new Date().toTimeString().slice(0, 5);
      var suffix = CLOCK.suffix ? ' ' + CLOCK.suffix : '';
      nodes.forEach(function (n) { n.textContent = t + suffix; });
    }
    tick();
    setInterval(tick, 15000);
  })();

  /* ------------------------------------------------------------------ *
   * 3. Word splitter for headline reveals
   * ------------------------------------------------------------------ */
  function splitWords(el) {
    if (el.dataset.split === 'done') return $$('.w', el);
    (function walk(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (child) {
        if (child.nodeType === 3) {
          var parts = child.textContent.split(/(\s+)/);
          var frag = document.createDocumentFragment();
          parts.forEach(function (p) {
            if (!p) return;
            if (/^\s+$/.test(p)) { frag.appendChild(document.createTextNode(p)); return; }
            var s = document.createElement('span');
            s.className = 'w';
            s.textContent = p;
            frag.appendChild(s);
          });
          child.parentNode.replaceChild(frag, child);
        } else if (child.nodeType === 1 && child.tagName !== 'BR') {
          walk(child);
        }
      });
    })(el);
    el.dataset.split = 'done';
    return $$('.w', el);
  }

  /* ------------------------------------------------------------------ *
   * 4. Reveal vocabulary
   *
   *    fade      y + opacity          generic copy
   *    lines     word-by-word         headlines
   *    wipe      clip-path from left  images
   *    draw-x    scaleX from left     hairlines
   *    slide-l/r x + opacity          marquee bars
   *    cards     grid-ordered stagger work grid
   *    skills    fast list stagger    skills list
   *    stagger   children fade-up     link/icon rows
   *    row       clip wipe + lift     case-study rows
   * ------------------------------------------------------------------ */
  var SET = {
    fade:      { opacity: 0, y: 24 },
    lines:     { opacity: 0 },
    wipe:      { clipPath: 'inset(0 100% 0 0)' },
    'draw-x':  { scaleX: 0 },
    'slide-l': { opacity: 0, x: -60 },
    'slide-r': { opacity: 0, x: 60 },
    cards:     { opacity: 1 },
    skills:    { opacity: 1 },
    stagger:   { opacity: 1 },
    row:       { opacity: 0, clipPath: 'inset(0 100% 0 0)' }
  };

  function prepare(scope) {
    if (REDUCED) return;
    $$('[data-anim]', scope).forEach(function (el) {
      var kind = el.dataset.anim;
      if (kind === 'cards')        gsap.set(el.children, { opacity: 0, y: 22, scale: .97 });
      else if (kind === 'skills')  gsap.set(el.children, { opacity: 0, y: 10 });
      else if (kind === 'stagger') gsap.set(el.children, { opacity: 0, y: 12 });
      if (SET[kind]) gsap.set(el, SET[kind]);
      if (kind === 'row') gsap.set($('.case-row__line', el), { scaleY: 0 });
    });
  }

  function play(el, tl, at) {
    var kind = el.dataset.anim;
    at = at || 0;
    switch (kind) {
      case 'lines':
        gsap.set(el, { opacity: 1 });
        tl.from(splitWords(el), {
          y: 26, opacity: 0, duration: 1.05, ease: 'expo.out', stagger: .05
        }, at);
        break;
      case 'wipe':
        tl.to(el, { clipPath: 'inset(0 0% 0 0)', duration: 1.25, ease: 'expo.out' }, at);
        break;
      case 'draw-x':
        tl.to(el, { scaleX: 1, duration: .95, ease: 'expo.out' }, at);
        break;
      case 'slide-l':
      case 'slide-r':
        tl.to(el, { x: 0, opacity: 1, duration: 1.2, ease: 'expo.out' }, at);
        break;
      case 'cards':
        tl.to(el.children, {
          opacity: 1, y: 0, scale: 1, duration: .9, ease: 'expo.out',
          stagger: { each: .09, from: 'start' }
        }, at);
        break;
      case 'skills':
        tl.to(el.children, { opacity: 1, y: 0, duration: .6, ease: 'power2.out', stagger: .03 }, at);
        break;
      case 'stagger':
        tl.to(el.children, { opacity: 1, y: 0, duration: .65, ease: 'expo.out', stagger: .05 }, at);
        break;
      case 'row':
        tl.to(el, { opacity: 1, clipPath: 'inset(0 0% 0 0)', duration: .9, ease: 'expo.out' }, at);
        tl.to($('.case-row__line', el), { scaleY: 1, duration: .7, ease: 'power2.out' }, at + .25);
        break;
      default:
        tl.to(el, { opacity: 1, y: 0, duration: 1, ease: 'expo.out' }, at);
    }
  }

  /* Each block gets its own trigger so it animates as it scrolls into view
     rather than the whole section firing at once. */
  function revealSection(section) {
    if (REDUCED) return;   // nothing was hidden, nothing to play
    $$('[data-anim]', section).forEach(function (el, i) {
      ScrollTrigger.create({
        trigger: el,
        start: 'top 88%',
        once: true,
        onEnter: function () {
          play(el, gsap.timeline({
            delay: Math.min(i * .06, .3),
            defaults: { force3D: true }
          }), 0);
        }
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * 4b. Light travelling down the background grid
   *
   * One or two comets at a time, on a random line, starting at a random
   * point, running a random distance that never exceeds the viewport
   * height, then fading out. Colour comes from the section's own --beam.
   * ------------------------------------------------------------------ */
  function rand(min, max) { return min + Math.random() * (max - min); }

  function setupGridBeams() {
    if (REDUCED) return;
    var hosts = $$('.grid-lines__inner');
    if (!hosts.length) return;

    var MAX_LIVE = 2;
    var live = 0;

    function spawn() {
      var pool = hosts.filter(function (h) {
        var b = h.getBoundingClientRect();
        return b.width > 0 && b.bottom > 0 && b.top < window.innerHeight;
      });
      if (!pool.length) return;

      var host  = pool[(Math.random() * pool.length) | 0];
      var hostH = host.getBoundingClientRect().height;
      var vh    = window.innerHeight;

      var len   = rand(140, Math.min(vh * 0.5, 340));            // comet + tail
      var trip  = rand(180, Math.min(vh, Math.max(240, hostH))); // never > one screen
      var from  = rand(-len, Math.max(0, hostH - trip * 0.4));
      // Duration follows the distance so every comet drifts at the same slow
      // pace, whatever length it drew.
      var dur   = trip / rand(46, 68);

      var beam = document.createElement('i');
      beam.className = 'grid-beam';
      beam.style.left = 'calc(' + ((Math.random() * 12) | 0) + ' * (100% - 1px) / 11)';
      beam.style.height = len.toFixed(0) + 'px';
      host.appendChild(beam);
      live++;

      gsap.timeline({ onComplete: function () { beam.remove(); live--; } })
        .fromTo(beam, { y: from, opacity: 0 }, { y: from + trip, duration: dur, ease: 'none' }, 0)
        .to(beam, { opacity: 1, duration: dur * .28, ease: 'power1.out' }, 0)
        .to(beam, { opacity: 0, duration: dur * .42, ease: 'power1.in' }, dur * .58);
    }

    (function loop() {
      setTimeout(function () {
        if (live < MAX_LIVE && !document.hidden) spawn();
        loop();
      }, rand(1400, 5200));
    })();
  }

  /* ------------------------------------------------------------------ *
   * 5. Marquee — duplicate track content, speed derived from width
   * ------------------------------------------------------------------ */
  function setupMarquees() {
    $$('[data-marquee]').forEach(function (track, i) {
      var item = $('.marquee__item', track);
      if (!item) return;
      var clone = item.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
      var width = item.getBoundingClientRect().width;
      var SPEED = Math.max(4, CFG.marqueeSpeed || 20);   // px per second
      var drift = i === 0 ? 1 : 1.15;    // the yellow row runs slightly slower
      track.style.animationDuration = Math.round((width / SPEED) * drift) + 's';
    });
  }

  /* ------------------------------------------------------------------ *
   * 6. Visual work filter
   * ------------------------------------------------------------------ */
  /* Each filter has its own server-rendered grid holding that filter's own
     newest items, so switching is a visibility swap rather than a re-query. */
  function setupFilters() {
    var groups = $$('[data-works] [data-group]');
    if (!groups.length) return;

    $$('[data-filter]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var cat = btn.dataset.filter;

        $$('[data-filter]').forEach(function (b) {
          var on = b === btn;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-selected', on ? 'true' : 'false');
        });

        groups.forEach(function (group) {
          var show = group.dataset.group === cat;
          group.hidden = !show;

          if (show && !REDUCED) {
            gsap.fromTo(group.children,
              { opacity: 0, y: 16 },
              { opacity: 1, y: 0, duration: .55, ease: 'expo.out', stagger: .05 });
          }
        });

        ScrollTrigger.refresh();
        lenis.resize();
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * 7. Footer "More Links"
   * ------------------------------------------------------------------ */
  function setupFooterMore() {
    var btn = $('[data-more]');
    var extra = $('#footerExtra');
    if (!btn || !extra) return;

    extra.hidden = true;

    function close() {
      if (extra.hidden) return;
      extra.hidden = true;
      btn.setAttribute('aria-expanded', 'false');
    }
    function open() {
      extra.hidden = false;
      btn.setAttribute('aria-expanded', 'true');
      if (!REDUCED) {
        gsap.fromTo(extra, { opacity: 0, y: -8 }, { opacity: 1, y: 0, duration: .3, ease: 'expo.out' });
        gsap.fromTo(extra.children, { opacity: 0, x: -6 },
          { opacity: 1, x: 0, duration: .35, ease: 'expo.out', stagger: .03 });
      }
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (extra.hidden) open(); else close();
    });
    extra.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', close);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }

  /* ------------------------------------------------------------------ *
   * 8. Section 05 — pinned scroll through 4 steps
   * ------------------------------------------------------------------ */
  function setupPinnedSteps() {
    var section = $('#how');
    var stepsHost = $('[data-steps]');
    var ghost = $('[data-ghost] span');
    if (!section || !stepsHost) return;
    var steps = $$('.how-step', stepsHost);
    var STEP_COUNT = steps.length;
    var current = -1;
    if (STEP_COUNT < 2) return;

    function setStep(i) {
      if (i === current) return;
      current = i;
      steps.forEach(function (s, n) {
        s.classList.toggle('is-active', n === i);
        s.classList.toggle('is-past', n < i);
      });
      if (!ghost) return;
      var label = '0' + (i + 1);
      if (REDUCED) { ghost.textContent = label; return; }
      gsap.to(ghost, {
        opacity: 0, y: 18, duration: .22, ease: 'power2.in',
        onComplete: function () {
          ghost.textContent = label;
          gsap.fromTo(ghost, { opacity: 0, y: -18 }, { opacity: 1, y: 0, duration: .38, ease: 'expo.out' });
        }
      });
    }

    var mm = gsap.matchMedia();
    mm.add('(min-width: 1024px)', function () {
      var st = ScrollTrigger.create({
        trigger: section,
        start: 'top top',
        end: function () { return '+=' + (window.innerHeight * (STEP_COUNT - 1) * 1.1); },
        pin: true,
        pinSpacing: true,
        anticipatePin: 1,
        snap: REDUCED ? false : {
          snapTo: [0.125, 0.375, 0.625, 0.875],
          duration: { min: 0.15, max: 0.45 },
          delay: 0.05,
          ease: 'power1.inOut'
        },
        onUpdate: function (self) {
          setStep(Math.max(0, Math.min(STEP_COUNT - 1, Math.floor(self.progress * STEP_COUNT))));
        }
      });
      setStep(0);
      return function () { st.kill(); };
    });

    mm.add('(max-width: 1023px)', function () {
      steps.forEach(function (s) { s.classList.add('is-active'); s.classList.remove('is-past'); });
      return function () {};
    });
  }

  /* ------------------------------------------------------------------ *
   * 9. Header: hero bar -> floating pill
   * ------------------------------------------------------------------ */
  function setupHeader() {
    var hero = $('#heroHeader');
    var sticky = $('#stickyHeader');
    if (!sticky) return;

    ScrollTrigger.create({
      start: 'top -120',
      end: 99999,
      onUpdate: function (self) { sticky.classList.toggle('is-visible', self.scroll() > 120); }
    });

    if (hero && !REDUCED) {
      gsap.to(hero, {
        opacity: 0, y: -26, ease: 'none',
        scrollTrigger: { trigger: '#top', start: 'top top', end: '+=180', scrub: true }
      });
    }
  }

  /* ------------------------------------------------------------------ *
   * 10. Mobile drawer + anchor scrolling
   * ------------------------------------------------------------------ */
  function setupDrawer() {
    var drawer = $('#drawer');
    if (!drawer) return;
    function open() {
      drawer.hidden = false;
      requestAnimationFrame(function () { drawer.classList.add('is-open'); });
      lenis.stop();
    }
    function close() {
      drawer.classList.remove('is-open');
      if (!document.documentElement.classList.contains('lock')) lenis.start();
      setTimeout(function () { drawer.hidden = true; }, 500);
    }
    $$('[data-drawer-open]').forEach(function (b) { b.addEventListener('click', open); });
    $$('[data-drawer-close]').forEach(function (b) { b.addEventListener('click', close); });
    $$('.drawer__nav a').forEach(function (a) { a.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }

  function setupAnchors() {
    document.addEventListener('click', function (e) {
      var a = e.target.closest && e.target.closest('a[href^="#"]');
      if (!a) return;
      var id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      if (document.documentElement.classList.contains('lock')) reveal();
      setTimeout(function () { lenis.scrollTo(target, { offset: -20, duration: 1.2 }); }, 60);
    });
  }

  /* ------------------------------------------------------------------ *
   * 11. First load: hero only, rest arrives on the first scroll intent
   * ------------------------------------------------------------------ */
  var revealed = false;

  /* Everything that only matters once the deferred half of the page is on
     screen. Inner pages call it straight away; the home page waits. */
  function initDeferred(scope) {
    setupMarquees();
    prepare(scope);
    $$('.marquee, .section, .cta, .footer', scope).forEach(revealSection);
    setupFilters();
    setupPinnedSteps();

    // NOTE: never put a transform on #rest — it would become the containing
    // block for ScrollTrigger's position:fixed pin inside section 05.
    ScrollTrigger.refresh();
    lenis.resize();   // pin spacing just changed the document height
  }

  function reveal() {
    if (revealed) return;
    revealed = true;

    document.documentElement.classList.remove('lock');
    var rest = $('#rest');
    rest.classList.add('is-revealed');
    lenis.start();

    initDeferred(rest);

    ['wheel', 'touchmove', 'keydown'].forEach(function (ev) {
      window.removeEventListener(ev, onIntent, { passive: true });
    });
  }

  function onIntent(e) {
    if (e.type === 'keydown') {
      var keys = ['ArrowDown', 'PageDown', 'End', ' ', 'Spacebar'];
      if (keys.indexOf(e.key) === -1) return;
    }
    if (e.type === 'wheel' && e.deltaY <= 0) return;
    reveal();
  }

  function armFirstScroll() {
    window.addEventListener('wheel', onIntent, { passive: true });
    window.addEventListener('touchmove', onIntent, { passive: true });
    window.addEventListener('keydown', onIntent);
    var hint = $('[data-scroll-hint]');
    if (hint) hint.addEventListener('click', function () {
      reveal();
      setTimeout(function () { lenis.scrollTo('#about', { duration: 1.4 }); }, 120);
    });
    lenis.stop();
  }

  /* ------------------------------------------------------------------ *
   * 12. Boot
   * ------------------------------------------------------------------ */
  function boot() {
    setupDrawer();
    setupAnchors();
    setupFooterMore();
    setupHeader();
    setupGridBeams();

    var hero = $('.hero');
    var rest = $('#rest');

    if (hero) {
      prepare(hero);

      if (!REDUCED) {
        var tl = gsap.timeline({ delay: .15, defaults: { force3D: true } });
        $$('[data-anim]', hero).forEach(function (el, i) { play(el, tl, i * .09); });
      }
    }

    if (rest) {
      armFirstScroll();
    } else {
      // Not the home page: nothing is deferred, so bring it all up at once.
      document.documentElement.classList.remove('lock');
      initDeferred(document.body);
    }

    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(function () { ScrollTrigger.refresh(); });
    }
    window.addEventListener('resize', function () {
      clearTimeout(window.__mproRs);
      window.__mproRs = setTimeout(function () { ScrollTrigger.refresh(); }, 200);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
