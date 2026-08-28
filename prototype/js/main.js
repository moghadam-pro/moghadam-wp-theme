/* ==========================================================================
   Moghadam.pro — prototype behaviour
   Deps (CDN, loaded before this file): gsap, ScrollTrigger, Lenis
   ========================================================================== */
(function () {
  'use strict';

  var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  // Without the CDN deps the page stays a plain, scrollable document.
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof Lenis === 'undefined') {
    document.documentElement.classList.remove('js', 'lock');
    return;
  }

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
   * 3. Hero terminal typing
   * ------------------------------------------------------------------ */
  var TERMINAL_LINES = [
    '[22:43:45.86][STRATEGY]Defining product goals and success metrics... Done',
    '[22:43:46.36][UX]Get:5 design://user-flows stable InRelease [12.8 kB]',
    '[22:43:46.80][UX]Get:6 design://information-architecture stable InRelease [16.1 kB]',
    '[22:43:47.20][UX]Building journey map... Done',
    '[22:43:48.27][UX]Resolving navigation dependencies... Done'
  ];

  function esc(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  function colorize(line) {
    return esc(line)
      .replace(/\[(STRATEGY|UX|RESEARCH)\]/g, '<span class="t-tag">[$1]</span>')
      .replace(/\bDone\b/g, '<span class="t-ok">Done</span>');
  }

  function runTerminal() {
    var host = $('[data-terminal]');
    if (!host) return;
    host.innerHTML = '';
    if (REDUCED) {
      TERMINAL_LINES.forEach(function (l) {
        var p = document.createElement('p');
        p.innerHTML = colorize(l);
        host.appendChild(p);
      });
      return;
    }
    var li = 0;
    function nextLine() {
      if (li >= TERMINAL_LINES.length) return;
      var text = TERMINAL_LINES[li];
      var p = document.createElement('p');
      host.appendChild(p);
      var i = 0;
      (function typeChar() {
        i++;
        p.innerHTML = esc(text.slice(0, i)) + '<span class="terminal__caret"></span>';
        if (i < text.length) {
          setTimeout(typeChar, 7 + Math.random() * 9);
        } else {
          p.innerHTML = colorize(text);
          li++;
          setTimeout(nextLine, 180);
        }
      })();
    }
    nextLine();
  }

  /* ------------------------------------------------------------------ *
   * 4. Word splitter for headline reveals
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
   * 5. Reveal animations
   * ------------------------------------------------------------------ */
  function animateIn(el, delay) {
    if (REDUCED) { gsap.set(el, { clearProps: 'all', opacity: 1, y: 0 }); return; }
    if (el.dataset.anim === 'lines') {
      var words = splitWords(el);
      gsap.set(el, { opacity: 1 });
      gsap.from(words, {
        y: 22, opacity: 0, duration: .85, ease: 'expo.out',
        stagger: .028, delay: delay || 0, force3D: true
      });
    } else {
      gsap.to(el, { opacity: 1, y: 0, duration: .8, ease: 'expo.out', delay: delay || 0 });
    }
  }

  function prepare(scope) {
    $$('[data-anim]', scope).forEach(function (el) {
      if (el.dataset.anim === 'lines') gsap.set(el, { opacity: 0 });
      else gsap.set(el, { opacity: 0, y: 24 });
    });
  }

  function observeSection(section) {
    var items = $$('[data-anim]', section);
    if (!items.length) return;
    ScrollTrigger.create({
      trigger: section,
      start: 'top 78%',
      once: true,
      onEnter: function () {
        items.forEach(function (el, i) { animateIn(el, Math.min(i * 0.06, 0.5)); });
      }
    });
  }

  /* ------------------------------------------------------------------ *
   * 6. Marquee — duplicate track content, speed derived from width
   * ------------------------------------------------------------------ */
  function setupMarquees() {
    $$('[data-marquee]').forEach(function (track, i) {
      var item = $('.marquee__item', track);
      if (!item) return;
      var clone = item.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
      var width = item.getBoundingClientRect().width;
      var SPEED = 38;            // px per second — deliberately slow
      var drift = i === 0 ? 1 : 1.18;   // second row drifts a little slower
      track.style.animationDuration = Math.round((width / SPEED) * drift) + 's';
    });
  }

  /* ------------------------------------------------------------------ *
   * 7. Visual works filter
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
          var show = cat === 'all' || card.dataset.cat === cat;
          card.classList.toggle('is-hidden', !show);
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
      if (ghost) {
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
   * 9. Header: hero -> sticky pill
   * ------------------------------------------------------------------ */
  function setupHeader() {
    var hero = $('#heroHeader');
    var sticky = $('#stickyHeader');
    if (!sticky) return;

    ScrollTrigger.create({
      start: 'top -120',
      end: 99999,
      onUpdate: function (self) {
        sticky.classList.toggle('is-visible', self.scroll() > 120);
      }
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
    $$('#rest .section, #rest .cta, #rest .footer, #rest .marquee').forEach(observeSection);
    setupFilters();
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

    // hero intro sequence
    var items = $$('[data-anim]', hero);
    if (REDUCED) {
      items.forEach(function (el) { animateIn(el, 0); });
      runTerminal();
    } else {
      items.forEach(function (el, i) { animateIn(el, 0.15 + i * 0.09); });
      setTimeout(runTerminal, 900);
    }

    // safety: if fonts land late, re-measure
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
