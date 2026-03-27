# Design System Strategy: CYT Comunicaciones

## 1. Overview & Creative North Star

### The Creative North Star: "The Digital Strategist"
This design system is built to reflect the intersection of high-performance technology and strategic partnership. We are moving away from the "generic SaaS" look of flat cards and heavy borders. Instead, we embrace **The Digital Strategist**—a visual language defined by editorial-grade typography, layered glass surfaces, and high-energy tonal depth.

To achieve a "bespoke" feel, we break the traditional grid through intentional asymmetry. Elements should overlap subtly; a primary action card might partially bleed into a section transition, or a display heading might sit offset from its supporting copy. We trade structural rigidity for organic, fluid professionalism that feels intentional rather than templated.

---

## 2. Colors & Surface Strategy

Our palette is anchored by the tension between **Vibrant Primary (#6713E1)** and **Secondary Electric Blue (#006685)**, balanced against a sophisticated, violet-tinted neutral scale.

### The "No-Line" Rule
To maintain a premium, high-end feel, **1px solid borders are strictly prohibited for sectioning.** We define boundaries through background color shifts.
- Use `surface-container-low` (#f8f1ff) sections sitting on a `surface` (#fdf7ff) background to imply separation.
- Visual rhythm is created through contrast, not ink.

### Surface Hierarchy & Nesting
Think of the UI as a series of physical layers—stacked sheets of frosted glass.
*   **Base:** `surface` (#fdf7ff)
*   **Sub-Section:** `surface-container-low` (#f8f1ff)
*   **Primary Container:** `surface-container-high` (#eee4ff)
*   **Floating Elements:** `surface-container-lowest` (#ffffff)

### The "Glass & Gradient" Rule
For high-impact components (Hero sections, floating dashboards), use Glassmorphism. 
*   **Recipe:** `surface` at 60% opacity + 24px Backdrop Blur.
*   **Signature Textures:** Main CTAs should never be flat. Use a linear gradient (45°) from `primary` (#6713E1) to `primary_container` (#803efa) to add a sense of "technological glow."

---

## 3. Typography: The Editorial Scale

We utilize **Manrope** exclusively. Its geometric yet warm construction provides the "Strategic Partner" personality—reliable but modern.

*   **Display (lg/md):** Reserved for hero moments. Use tight letter-spacing (-0.02em) to create an authoritative, editorial impact.
*   **Headlines:** These are the anchors. Use `headline-lg` (2rem) for major section transitions, ensuring enough vertical breathing room (at least `spacing-16`) to let the type "own" the space.
*   **Title vs. Body:** Titles carry the brand's energy; use semi-bold weights. Body text stays in `body-lg` (1rem) for maximum readability, utilizing `on_surface_variant` (#4a4456) to reduce visual fatigue while maintaining high contrast.

The hierarchy is intentionally dramatic. A large `display-lg` heading paired with a small, uppercase `label-md` creates a sophisticated "High-End Editorial" contrast.

---

## 4. Elevation & Depth

### The Layering Principle
Depth is achieved through Tonal Layering. To elevate a card, do not reach for a shadow first; reach for the next tier in the surface scale. Place a `surface-container-lowest` card on a `surface-container-low` background.

### Ambient Shadows
When true elevation is required for floating modals or dropdowns:
*   **Blur:** 40px to 60px.
*   **Opacity:** 4% - 8%.
*   **Color:** Tint the shadow with `on_surface` (#210853). Avoid pure black shadows; they look "muddy" on a light-mode violet palette.

### The "Ghost Border" Fallback
If accessibility requires a border, use a **Ghost Border**: `outline_variant` (#ccc3d8) at **15% opacity**. This provides a hint of structure without interrupting the fluid glass aesthetic.

---

## 5. Components

### Buttons
*   **Primary:** High-energy gradient (Primary to Primary Container), `rounded-full`, white text. No border.
*   **Secondary:** Glassmorphism style. `surface-container-highest` with a 10% opacity `primary` tint.
*   **States:** On hover, increase the gradient intensity. On click, subtle scale down (98%).

### Input Fields
*   **Style:** `surface-container-low` backgrounds. No borders until focused.
*   **Focus State:** A 2px "glow" using `primary` at 30% opacity.
*   **Error State:** Use `error` (#ba1a1a) text but avoid red boxes. Use a soft red tint in the background to indicate the field area.

### Cards & Lists
*   **Forbid Dividers:** Never use horizontal lines to separate list items. Use `spacing-4` vertical gaps or alternating background tints (`surface` vs `surface-container-low`).
*   **Roundedness:** All cards must use `rounded-xl` (1.5rem) to maintain the modern, approachable vibe.

### Signature Component: The "Performance Metric" Chip
For a B2B SaaS context, metrics need to feel high-performance. Use `tertiary_fixed` (#42fed0) backgrounds with `on_tertiary_fixed` (#002018) text for positive growth data. The vibrancy of the teal against the violet background provides that "high energy" requested.

---

## 6. Do’s and Don’ts

### Do:
*   **Embrace Negative Space:** If a section feels crowded, double the padding. Premium is defined by the "luxury of space."
*   **Use Subtle Gradients:** Use the `secondary` (#006685) to `secondary_container` (#59cefe) gradient for data visualizations and secondary actions.
*   **Align to a Soft Grid:** While layouts are asymmetrical, keep internal element spacing strictly to the `8pt` scale (Spacing Scale).

### Don’t:
*   **Don't use 100% Black:** For text, use `on_background` (#210853). It keeps the interface feeling "deep" and "technological" rather than "stark."
*   **Don't use Red for highlights:** Red is reserved strictly for destructive errors. Use the Vibrant Purple or Teal for all "high energy" highlights.
*   **Don't use Dark Mode:** This system is optimized for a light, "Airy" professional experience. Dark surfaces should only be used as small "anchor" accents (e.g., a dark footer using `inverse_surface`).