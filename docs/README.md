# Moghadam Theme Documentation

This directory is the documentation home for the current theme on `main`.

## Current source of truth

For the current implementation and supported behavior, use:

- the root [README](../README.md)
- the root [CHANGELOG](../CHANGELOG.md)
- the current theme source on `main`

The former standalone `docs` orphan branch described the project around theme
version 1.2.0. Its content is preserved here for historical context, but it must
not override newer behavior in the current theme.

## Historical archive

- [v1.2 project brief](archive/v1.2/project-brief.md)
- [v1.2 decision log](archive/v1.2/decisions.md)
- [v1.2 architecture](archive/v1.2/architecture.md)
- [v1.2 templates](archive/v1.2/templates.md)
- [v1.2 conventions](archive/v1.2/conventions.md)
- [v1.2 roadmap](archive/v1.2/roadmap.md)
- [former docs-branch README](archive/v1.2/docs-branch-README.md)
- [former docs-branch changelog](archive/v1.2/CHANGELOG.md)
- [session 01](archive/sessions/2026-08-24-session-01.md)
- [session 02](archive/sessions/2026-08-24-session-02.md)
- [prototype v1](archive/prototype-v1/README.md)

## Superseded Git policy

Historical decision **D-009** records the old policy of keeping documentation on
a permanent orphan `docs` branch. That policy is superseded.

The repository now follows one-source-of-truth Git discipline:

```text
main
├── short-lived feat/*
├── short-lived fix/*
├── short-lived docs/*
├── short-lived chore/*
└── short-lived release/*
```

Documentation changes are merged into `main` through pull requests and their
working branches are deleted after verification. Historical checkpoints belong
in tags, not permanent backup or documentation branches.
