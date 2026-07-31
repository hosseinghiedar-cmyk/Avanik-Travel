# Avanik Architecture

## Frozen decisions

1. WordPress custom theme under `wordpress/avanik/`.
2. Source code under `src/`.
3. Sass uses `@use` and `@forward`.
4. Components use the `av-` BEM naming convention.
5. RTL is the primary layout direction.
6. The approved navy/gold visual identity remains unchanged.
7. Existing files are extended rather than structurally replaced.
8. Compiled CSS/JS are generated into the WordPress theme `assets` directory.

## Source tree

```text
Avanik-Travel/
├── docs/
├── src/
│   ├── scss/
│   ├── js/
│   ├── fonts/
│   ├── icons/
│   └── images/
├── build/
└── wordpress/
    └── avanik/
```
