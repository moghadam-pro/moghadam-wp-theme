/* ==========================================================================
   Moghadam.pro — prototype behaviour
   Deps (CDN, loaded before this file): gsap, ScrollTrigger, Lenis

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
        timeZone: 'Europe/Istanbul', hour: '2-digit', minute: '2-digit', hour12: false
      });
    } catch (e) { fmt = null; }
    function tick() {
      var t = fmt ? fmt.format(new Date()) : new Date().toTimeString().slice(0, 5);
      nodes.forEach(function (n) { n.textContent = t + ' IST'; });
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
          y: 22, opacity: 0, duration: .8, ease: 'expo.out', stagger: .026
        }, at);
        break;
      case 'wipe':
        tl.to(el, { clipPath: 'inset(0 0% 0 0)', duration: .95, ease: 'expo.out' }, at);
        break;
      case 'draw-x':
        tl.to(el, { scaleX: 1, duration: .7, ease: 'expo.out' }, at);
        break;
      case 'slide-l':
      case 'slide-r':
        tl.to(el, { x: 0, opacity: 1, duration: 1, ease: 'expo.out' }, at);
        break;
      case 'cards':
        tl.to(el.children, {
          opacity: 1, y: 0, scale: 1, duration: .7, ease: 'expo.out',
          stagger: { each: .05, from: 'start' }
        }, at);
        break;
      case 'skills':
        tl.to(el.children, { opacity: 1, y: 0, duration: .45, ease: 'power2.out', stagger: .018 }, at);
        break;
      case 'stagger':
        tl.to(el.children, { opacity: 1, y: 0, duration: .5, ease: 'expo.out', stagger: .035 }, at);
        break;
      case 'row':
        tl.to(el, { opacity: 1, clipPath: 'inset(0 0% 0 0)', duration: .7, ease: 'expo.out' }, at);
        tl.to($('.case-row__line', el), { scaleY: 1, duration: .5, ease: 'power2.out' }, at + .2);
        break;
      default:
        tl.to(el, { opacity: 1, y: 0, duration: .75, ease: 'expo.out' }, at);
    }
  }

  /* Per-section choreography: each entry returns the delay for an element.
     Anything not listed just plays in document order. */
  var CHOREO = {
    about: function (el, i) {
      if (el.dataset.anim === 'wipe') return .15;         // portrait wipes early
      return i * 0.07;
    },
    cases: function (el, i) {
      return el.dataset.anim === 'row' ? .25 + (i - 3) * .09 : i * .07;
    },
    works: function (el, i) { return i * .1; },
    how:   function (el, i) { return i * .08; },
    contact: function (el, i) { return i * .12; }
  };

  function revealSection(section) {
    var items = $$('[data-anim]', section);
    if (!items.length) return;
    var choreo = CHOREO[section.id];

    if (REDUCED) return;   // nothing was hidden, nothing to play

    ScrollTrigger.create({
      trigger: section,
      start: 'top 82%',
      once: true,
      onEnter: function () {
        var tl = gsap.timeline({ defaults: { force3D: true } });
        items.forEach(function (el, i) {
          play(el, tl, choreo ? choreo(el, i) : Math.min(i * .07, .6));
        });
      }
    });
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
      var SPEED = 20;                    // px per second — a slow drift
      var drift = i === 0 ? 1 : 1.15;    // the yellow row runs slightly slower
      track.style.animationDuration = Math.round((width / SPEED) * drift) + 's';
    });
  }

  /* ------------------------------------------------------------------ *
   * 6. Visual work filter
   * ------------------------------------------------------------------ */
  function setupFilters() {
    var cards = $$('[data-works] .work-card');
    $$('[data-filter]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var cat = btn.dataset.filter;
        $$('[data-filter]').forEach(function (b) {
          var on = b === btn;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        cards.forEach(function (card) {
          card.classList.toggle('is-hidden', !(cat === 'all' || card.dataset.cat === cat));
        });
        if (!REDUCED) {
          gsap.fromTo(cards.filter(function (c) { return !c.classList.contains('is-hidden'); }),
            { opacity: 0, y: 16 },
            { opacity: 1, y: 0, duration: .5, ease: 'expo.out', stagger: .04 });
        }
        ScrollTrigger.refresh();
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
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
      if (open) {
        extra.hidden = true;
      } else {
        extra.hidden = false;
        if (!REDUCED) {
          gsap.fromTo(extra.children, { opacity: 0, y: 10 },
            { opacity: 1, y: 0, duration: .45, ease: 'expo.out', stagger: .03 });
        }
      }
      ScrollTrigger.refresh();
      lenis.resize();
    });
  }

  /* ------------------------------------------------------------------ *
   * 8. Section 05 — pinned scroll through 4 steps
   * ------------------------------------------------------------------ */
  var STEP_COUNT = 4;

  function setupPinnedSteps() {
    var section = $('#how');
    var stepsHost = $('[data-steps]');
    var ghost = $('[data-ghost] span');
    if (!section || !stepsHost) return;
    var steps = $$('.how-step', stepsHost);
    var current = -1;

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

  function reveal() {
    if (revealed) return;
    revealed = true;

    document.documentElement.classList.remove('lock');
    var rest = $('#rest');
    rest.classList.add('is-revealed');
    lenis.start();

    setupMarquees();
    prepare(rest);
    $$('#rest .marquee, #rest .section, #rest .cta, #rest .footer').forEach(revealSection);
    setupFilters();
    setupFooterMore();
    setupPinnedSteps();
    setupHeader();

    // NOTE: never put a transform on #rest — it would become the containing
    // block for ScrollTrigger's position:fixed pin inside section 05.
    ScrollTrigger.refresh();
    lenis.resize();   // pin spacing just changed the document height

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
    var hero = $('.hero');
    prepare(hero);

    setupDrawer();
    setupAnchors();
    armFirstScroll();

    if (!REDUCED) {
      var tl = gsap.timeline({ delay: .15, defaults: { force3D: true } });
      $$('[data-anim]', hero).forEach(function (el, i) { play(el, tl, i * .09); });
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
