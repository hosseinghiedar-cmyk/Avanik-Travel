# Avanik Travel

White-label travel booking platform for WordPress.

## Current version

`0.2.5`

## Architecture

- WordPress custom theme
- PHP 8.2+
- SCSS with Sass `@use` / `@forward`
- BEM naming
- RTL-first UI
- Vanilla JavaScript
- CSS custom properties for runtime theming

## Development

```bash
npm install
npm run build
```

The build writes compiled assets to:

```text
wordpress/avanik/assets/
```

## Important

The visual design and architecture are frozen according to the approved Avanik design system. New work should extend the existing system rather than replacing it.
