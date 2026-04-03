# Web Agency Project Plan

This document breaks down the implementation of the optimizations defined in the `optimization-plan.md` for the Volksbühne Worms website. It acts as an actionable timeline and task list for the web agency.

## Phase 1: Audit & Foundation (Weeks 1-2)

**Objective:** Establish baselines, configure tooling, and apply foundational fixes.

- [ ] **Task 1.1: Technical SEO & Core Web Vitals Audit**
  - Run Google Lighthouse / PageSpeed Insights on all existing pages.
  - Document baseline metrics (LCP, FID, CLS).
- [ ] **Task 1.2: Build Pipeline Setup**
  - Introduce an automated build step (e.g., using npm/vite or specialized PHP task runners) to handle CSS/JS minification.
  - Configure automated image conversion and compression (to WebP format).
- [ ] **Task 1.3: HTML Structure Review**
  - Audit `index.php` and `weihnachtsmaerchen.php` to ensure a strict, single `H1`, cascading `H2`-`H6` structure.
  - Fix any skipped heading ranks.

## Phase 2: Design Systems & UX (Weeks 3-4)

**Objective:** Align the UI with 2026 accessibility and design standards.

- [ ] **Task 2.1: Typography & Spacing Update**
  - Update `main.css` variables: ensure minimum `16px` body font size and `1.5` line height.
  - Increase margins/padding on main content blocks to ensure 30-40% white space.
- [ ] **Task 2.2: Mobile Navigation & Touch Targets**
  - Audit all buttons and links in the mobile view.
  - Standardize touch targets to minimum `48x48px` via CSS padding in `main.css`.
  - Implement a sticky, low-profile header for smooth scrolling on long pages.
- [ ] **Task 2.3: Accessibility (A11y) Pass**
  - Verify minimum 4.5:1 text contrast across all color variables.
  - Update `layout-start.php` and interactive elements in `main.js` with proper `aria-` attributes.
  - Ensure all `<img src="...">` tags have descriptive `alt` text.

## Phase 3: Content Strategy & SEO Injection (Weeks 5-6)

**Objective:** Optimize textual content for dwell time and search engine understanding.

- [ ] **Task 3.1: Content Formatting**
  - Refactor large text blocks in `index.php` and `weihnachtsmaerchen.php`: max 150 words per paragraph, utilizing list formats where appropriate.
  - Inject 40-60 word definitive target answers below key `H2` subheadings.
- [ ] **Task 3.2: Structured Data (Schema.org)**
  - Expand the `json_ld(...)` helper call in `weihnachtsmaerchen.php` to include full `Event` schema (dates, location, offers/tickets).
  - Add `LocalBusiness` schema to `index.php` representing the theater entity.
- [ ] **Task 3.3: Trust Signals Integration**
  - Design and implement a "Testimonials" or "Past Successes" component.
  - Add social proof elements to the ticket-buying section on `weihnachtsmaerchen.php` (e.g., "Fast ausverkauft in 2025").

## Phase 4: QA, Testing, & Deployment (Week 7)

**Objective:** Final validation before launch.

- [ ] **Task 4.1: Cross-Browser & Device Testing**
  - Test the site in Chrome, Safari, and Firefox.
  - Emulate various mobile device viewports to ensure responsive breakpoint integrity.
- [ ] **Task 4.2: Final Lighthouse Validation**
  - Re-run Core Web Vitals checks. Ensure all metrics are in the "Green" (Good) category.
- [ ] **Task 4.3: Deployment**
  - Deploy updated static assets, optimized images, and PHP views to the production environment.
  - Submit the sitemap/URLs to Google Search Console to encourage re-indexing.