# Website Optimization Plan 2026

This document outlines the optimization strategy for the Volksbühne Worms website (`vb-static-26`), based on web design, SEO, and content best practices for 2026.

## 1. SEO & Core Web Vitals Optimization

Expectations for 2026 revolve heavily around performance, mobile-first indexation, and structured data.

### 1.1 Technical Performance
- **Core Web Vitals:** Ensure LCP (Largest Contentful Paint) is < 2.5s, FID (First Input Delay) < 100ms, and CLS (Cumulative Layout Shift) < 0.1.
- **Image Optimization:** Migrate all images to modern formats like WebP or AVIF. Target file sizes should be <200KB for hero images and <50KB for thumbnails. Introduce lazy loading for off-screen images.
- **Resource Minification:** Since the project uses static CSS and JS, establish a build step to minify `main.css` and `main.js` for production.

### 1.2 Structural SEO
- **Semantic HTML & Headings:** Ensure strict hierarchical use of headers (one `H1` per page, followed logically by `H2`, `H3`). Avoid skipping heading levels to satisfy CSS aesthetics.
- **URL Structure:** Keep URLs human-readable, under 60 characters, and hyphen-separated. The current routing in `.htaccess` and `ci-router.php` supports this well, but ongoing content must adhere to it.
- **Schema Markup:** Expand JSON-LD usage. Currently, structured data is supported via `json_ld()` helper. Add specific schemas like `Event` for the "Weihnachtsmärchen" and `LocalBusiness` for the theater.

## 2. Content & Readability Best Practices

In 2026, user engagement and dwell time are critical SEO ranking factors, heavily influenced by how content is formatted.

- **Micro-Copy & Paragraphs:** Limit paragraphs to 3-4 sentences (max 150 words). Break up text with subheadings every 300 words.
- **Typography:** Ensure a minimum base font size of 16px with a line height of 1.5–1.6 for optimal readability on mobile and desktop. Maintain a high contrast ratio (minimum 4.5:1 text-to-background).
- **Scannability:** Utilize bullet points, lists, and strategic whitespace (aiming for 30-40% white space visually) to make the content easier to scan.
- **Immediate Value:** Place short, direct answers to common user questions (40-60 words) immediately following `H2` subheadings.

## 3. Layout & Modern UI/UX

Smooth interactions and an intuitive mobile experience are standard.

- **Mobile-First Approach:** Ensure all touch targets are at least 48x48px (especially important for the navigation menu and ticket purchase buttons).
- **Navigation:** Limit main navigation to 5-7 essential items. Consider implementing a sticky header for longer landing pages (e.g., `weihnachtsmaerchen.php`) to keep navigation and ticket purchasing accessible.
- **Trust Elements:** Integrate trust signals permanently on the pages. For the theater, this means customer reviews/testimonials, clear contact information, secure links, and perhaps a history of sold-out shows.
- **Accessibility (A11y):** Go beyond basic contrast. Ensure keyboard navigability, proper ARIA roles for custom elements, and descriptive `alt` texts for all theater images.

## Next Steps for the Current Codebase

To bring `vb-static-26` fully up to 2026 standards, the following adjustments are recommended:
1. **Assets:** Implement an automated image optimization and CSS/JS minification pipeline.
2. **Components:** Modify `partials/layout-start.php` to ensure the sticky navigation and mobile touch targets meet guidelines.
3. **Structured Data:** Inject `Event` JSON-LD directly into `weihnachtsmaerchen.php`.
