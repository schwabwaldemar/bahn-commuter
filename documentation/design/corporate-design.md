# Bahn Commuter Corporate Design System

## Brand Essence
- **Positioning**: Bahn Commuter keeps daily rail passengers informed about disruptions before they impact their commute.
- **Personality**: Calm, reliable, pragmatic, with a hint of optimism.
- **Voice**: Direct and conversational. Prefer plain language, short sentences, and action-oriented CTAs.

## Visual Identity

### Logo & Wordmark
- **Primary lockup**: "Bahn Commuter" set in Inter SemiBold, sentence case, tracking +10.
- **Icon**: Circular badge with inbound/outbound rail pictogram. Use `--color-brand-primary` background and white iconography.
- **Clear space**: Maintain padding equal to 1× the icon diameter around the lockup.
- **Minimum size**: 32×32px for digital; 12mm print.

### Colour Palette
Implement the palette through CSS custom properties (see `assets/styles/app.css`).

| Role | Hex | Usage |
| --- | --- | --- |
| `--color-brand-primary` | `#005BBB` | Primary CTAs, key highlights, active states. |
| `--color-brand-secondary` | `#FFB800` | Accent stripes, progress indicators. |
| `--color-neutral-900` | `#0F1C2E` | Headlines, navigation text. |
| `--color-neutral-600` | `#45546B` | Body copy, secondary text. |
| `--color-neutral-200` | `#D8DEE8` | Card borders, dividers. |
| `--color-neutral-050` | `#F4F6FA` | Card and page backgrounds. |
| `--color-success` | `#2EAC66` | Success banners, confirmation messages. |
| `--color-warning` | `#F08C00` | Service advisories. |
| `--color-error` | `#D64545` | Validation, critical alerts. |

- Maintain a minimum contrast ratio of 4.5:1 for body text. Pair brand primary with white or neutral-900 text only.
- Gradients: optional diagonal gradient `linear-gradient(135deg, #005BBB 0%, #0A74D1 100%)` for hero backgrounds.

### Typography
- **Primary typeface**: Inter (system fallback: `"Inter", "SF Pro Text", "Segoe UI", sans-serif`).
- **Display**: Inter SemiBold, 36–48px, tracking -1%.
- **Headings**: Inter SemiBold, 22–30px, tracking -0.5%.
- **Body**: Inter Regular, 16px/1.6.
- **Caption/Meta**: Inter Medium, 12–14px, uppercase allowed for labels.
- Avoid mixing too many weights; stick to Regular, Medium, SemiBold.

### Iconography & Illustration
- Use line icons with 2px stroke, rounded caps. Base icon size 24×24px.
- Illustration style is flat, minimal, focused on transportation motifs. Limit palette to brand primary, secondary, and neutrals.

## UI System

### Layout
- Base grid: 8px spacing unit. Components snap to 8/16/24px increments.
- Page container max-width: 960px with 24px horizontal padding on tablet/desktop, 16px on mobile.
- Sections separated by 40px vertical spacing.

### Components
- **Buttons**: Primary uses `--color-brand-primary` background, white text, 4px radius, 12px vertical padding. Hover darken by 8%.
- **Secondary button**: Outline 1px `--color-neutral-200`, text `--color-brand-primary`, background white, hover fill with `rgba(0,91,187,0.08)`.
- **Alerts**: Rounded 6px cards with left accent bar 4px wide keyed to status colour.
- **Cards**: White background, subtle drop shadow `0 8px 24px rgba(15,28,46,0.08)`, border radius 12px.
- **Forms**: Inputs full-width, 44px height minimum, 8px radius, border `1px solid --color-neutral-200`, focus ring `0 0 0 3px rgba(0,91,187,0.25)`.

### Navigation
- Header height 64px, sticky on scroll.
- Active navigation link indicated by 2px bottom border in `--color-brand-primary` and `font-weight: 600`.

### Data Visualization
- Primary data lines/bars use brand primary, comparisons use brand secondary and neutral-600.
- Background gridlines 1px `rgba(15,28,46,0.08)`.

## Tone & Content Guidelines
- Speak directly to the commuter (second-person "you").
- Highlight practical benefits up front (e.g., "Get disruption alerts before you leave").
- Keep CTAs short and imperative: "Create account", "Verify email".
- For alerts, lead with the line affected, then the impact, then the action commuters should take.

## Accessibility & Inclusivity
- Always provide text alternatives for icons and motion.
- Offer language toggle prominently; default EN/DE per user preference.
- Ensure focus outlines meet contrast ratio of at least 3:1 (use the provided focus ring colour).
- Avoid relying on colour alone. Pair status colours with icons and text labels.

## Motion Principles
- Keep animations under 200ms ease-out for hover/focus, 400ms for page transitions.
- Motion should communicate state change (e.g., loading spinner in brand secondary) and never loop infinitely without purpose.

## Assets & Delivery
- Export logos and icons in SVG; provide PNG fallbacks @2x.
- Store shared assets under `public/assets/brand/` mirroring the folder structure.
- Include colour and typography tokens in `assets/styles/design-tokens.css` for use in builds.

## Sample CSS Token Block
```css
:root {
  --color-brand-primary: #005BBB;
  --color-brand-secondary: #FFB800;
  --color-neutral-900: #0F1C2E;
  --color-neutral-600: #45546B;
  --color-neutral-200: #D8DEE8;
  --color-neutral-050: #F4F6FA;
  --color-success: #2EAC66;
  --color-warning: #F08C00;
  --color-error: #D64545;
  --font-family-base: "Inter", "SF Pro Text", "Segoe UI", sans-serif;
  --radius-md: 12px;
  --shadow-elevated: 0 8px 24px rgba(15, 28, 46, 0.08);
}
```

## Implementation Checklist
1. Introduce token file and import into `assets/styles/app.css`.
2. Update primary button styles and card spacing to match specifications.
3. Audit components for contrast and focus visibility.
4. Produce SVG logo pack (square icon, horizontal lockup).
5. Create hero illustration using brand palette (optional for v2).
