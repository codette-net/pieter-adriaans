# Pieter-Adriaans.com Legacy Migration Task List

## 1. Discovery & Inventory

- [ ] Audit the current procedural PHP application structure
- [ ] Map the full file tree
- [ ] Identify all public routes / URLs
- [ ] Identify all templates, includes, partials, and shared layout files
- [ ] Inventory all forms and POST handlers
- [ ] Inventory all database tables
- [ ] Identify all content entities (pages, artworks, categories, news, etc.)
- [ ] Identify all media assets and upload locations
- [ ] Document current authentication / admin access flows
- [ ] Document current dependencies on server config, `.htaccess`, cron jobs, mail, or third-party services
- [ ] Identify legacy business rules hidden in procedural files
- [ ] List SEO-critical pages and URLs
- [ ] Identify broken, duplicate, or unused parts of the current codebase

---

## 2. Migration Strategy Document

- [ ] Write context and goals for the rebuild
- [ ] Describe the current state of the legacy system
- [ ] Define the target state in Laravel
- [ ] Compare migration approaches:
    - [ ] Big bang rewrite
    - [ ] Phased rebuild
    - [ ] Strangler-style migration
- [ ] Choose the recommended approach
- [ ] Define decision criteria (risk, SEO, cost, time-to-value, complexity)
- [ ] Define what is in scope for phase 1
- [ ] Define what is explicitly out of scope
- [ ] List assumptions and open questions
- [ ] Define risks and mitigations
- [ ] Define rollback / fallback considerations

---

## 3. Functional Decomposition

- [ ] Break the legacy site into modules
- [ ] Identify which modules are public-facing
- [ ] Identify which modules require admin management
- [ ] Define module priority order

### Candidate modules
- [ ] Static pages
- [ ] Artwork / gallery
- [ ] Categories / collections
- [ ] News / blog
- [ ] Contact forms
- [ ] Admin/content management
- [ ] Media library
- [ ] SEO/meta management

---

## 4. Technical Foundation

- [ ] Create new Laravel project
- [ ] Configure environment setup
- [ ] Configure local development workflow
- [ ] Configure database connection
- [ ] Configure filesystem / uploads
- [ ] Configure mail settings
- [ ] Configure app URL and environment-specific settings
- [ ] Set up Git repository / branching strategy
- [ ] Set up base deployment approach
- [ ] Set up logging and error handling
- [ ] Set up basic backup strategy

---

## 5. Frontend Migration Approach

- [ ] Reuse the old HTML structure where possible
- [ ] Reuse legacy CSS as first-pass styling
- [ ] Extract repeated layout parts into Blade layouts and partials
- [ ] Convert static PHP templates into Blade views
- [ ] Replace legacy include logic with Blade components / partials
- [ ] Keep visual parity first
- [ ] Defer redesign work until after functional parity
- [ ] Identify frontend areas that may later benefit from Vue
- [ ] Mark progressive enhancement opportunities for later phases

### Progressive enhancement candidates
- [ ] Filters
- [ ] Search
- [ ] Interactive gallery views
- [ ] Admin-side dynamic UI
- [ ] Form enhancements

---

## 6. Data & Database Analysis

- [ ] Review existing table structure
- [ ] Review column meanings and hidden coupling in the schema
- [ ] Identify missing constraints / inconsistent naming
- [ ] Identify legacy fields that should be deprecated
- [ ] Define Laravel data model candidates
- [ ] Decide which tables can be reused temporarily
- [ ] Decide which tables should be redesigned
- [ ] Map legacy tables to Eloquent models
- [ ] Define migration strategy for existing data
- [ ] Define import / sync scripts if needed
- [ ] Plan data validation and cleanup

### Deliverables
- [ ] Legacy-to-new schema mapping
- [ ] Entity relationship overview
- [ ] Field mapping document
- [ ] Data cleanup checklist

---

## 7. Routing & SEO Preservation

- [ ] Export all current live URLs
- [ ] Categorize URLs by type
- [ ] Match current routes in Laravel where possible
- [ ] Create redirect plan for changed URLs
- [ ] Preserve slugs where possible
- [ ] Preserve metadata-critical pages
- [ ] Plan canonical tags
- [ ] Plan sitemap generation
- [ ] Plan robots.txt handling
- [ ] Check for legacy query-string URLs that need support or redirects
- [ ] Define 301 redirect mapping list

---

## 8. Filament Admin Planning

- [ ] Decide which content types will be managed in Filament
- [ ] Define admin roles and permissions
- [ ] Define CRUD resources needed
- [ ] Define media management workflow
- [ ] Define validation rules per content type
- [ ] Define publishing workflow (draft/published if needed)
- [ ] Define SEO fields needed in admin
- [ ] Define ordering/sorting needs
- [ ] Define relationship management needs
- [ ] Define custom Filament pages/widgets if needed

### Filament resources to likely create
- [ ] Pages
- [ ] Artworks
- [ ] Categories / collections
- [ ] News / blog posts
- [ ] Contact submissions
- [ ] Media / images
- [ ] Site settings

---

## 9. Incremental Build Plan

### Phase 0 — Safety & Baseline
- [ ] Make a full backup of files and database
- [ ] Capture screenshots of important current pages
- [ ] Record current functionality in a baseline checklist
- [ ] Record current SEO-critical URLs
- [ ] Record current forms and workflows
- [ ] Define minimum acceptance criteria for parity

### Phase 1 — Laravel Skeleton + Static Parity
- [ ] Set up Laravel app skeleton
- [ ] Build main layout in Blade
- [ ] Port header, footer, nav, and base templates
- [ ] Rebuild static pages first
- [ ] Make sure old styling still works
- [ ] Match key URLs
- [ ] Deploy first low-risk parity version in staging

### Phase 2 — Core Content Models
- [ ] Add Eloquent models for core entities
- [ ] Create migrations or temporary schema bridge
- [ ] Port gallery/artwork features
- [ ] Port categories/collections
- [ ] Port media rendering logic
- [ ] Build Filament CRUD for these entities
- [ ] Validate content parity against live site

### Phase 3 — Forms & Admin
- [ ] Migrate contact forms
- [ ] Add validation and spam protection
- [ ] Store submissions if needed
- [ ] Build remaining Filament resources
- [ ] Add role-based access if needed

### Phase 4 — Cleanup & Stabilization
- [ ] Remove duplicated legacy logic
- [ ] Refactor temporary bridge code
- [ ] Optimize Blade structure
- [ ] Improve validation and error handling
- [ ] Add tests for critical flows
- [ ] Prepare production launch checklist

### Phase 5 — Progressive Enhancement / Vue (Later)
- [ ] Reassess frontend pain points
- [ ] Introduce Vue only where it adds clear value
- [ ] Avoid rewriting stable Blade pages without reason
- [ ] Build interactive islands rather than full SPA by default
- [ ] Define API boundaries if Vue grows in scope

---

## 10. Architecture Decisions to Make

- [ ] Decide whether to reuse the legacy database short-term or redesign immediately
- [ ] Decide whether to keep URL structure 100% identical
- [ ] Decide whether to migrate module-by-module or page-by-page
- [ ] Decide whether to build custom admin screens or use Filament everywhere possible
- [ ] Decide whether Vue is optional enhancement or future core frontend layer
- [ ] Decide whether uploads stay on local disk or move to managed storage
- [ ] Decide how much refactoring is allowed before feature parity is reached

---

## 11. Quality & Testing

- [ ] Define smoke test checklist
- [ ] Define visual parity checks
- [ ] Define route parity checks
- [ ] Define form submission tests
- [ ] Define admin CRUD tests
- [ ] Define media rendering tests
- [ ] Define SEO validation checks
- [ ] Define pre-launch UAT checklist

---

## 12. Risk Management

- [ ] Risk: missing legacy business logic
- [ ] Risk: data inconsistency during migration
- [ ] Risk: SEO loss due to URL changes
- [ ] Risk: hidden dependencies in old includes
- [ ] Risk: upload path / media breakage
- [ ] Risk: scope creep from redesign discussions
- [ ] Risk: trying to introduce Vue too early
- [ ] Risk: over-modeling before understanding the legacy system

### Mitigations
- [ ] Prioritize parity before cleanup
- [ ] Work module by module
- [ ] Keep old HTML/CSS initially
- [ ] Preserve URLs where possible
- [ ] Validate with real data early
- [ ] Use staging for each milestone
- [ ] Delay major frontend changes until backend is stable

---

## 13. Deliverables

- [ ] Legacy system inventory
- [ ] Route / URL mapping document
- [ ] Database mapping document
- [ ] Migration strategy document
- [ ] Laravel project skeleton
- [ ] Blade-based frontend parity
- [ ] Filament admin for core content
- [ ] Redirect plan
- [ ] Launch checklist
- [ ] Post-launch cleanup backlog

---

## 14. Open Questions

- [ ] What are the exact content types in the current site?
- [ ] Is there an existing admin panel, or is content edited directly in code/database?
- [ ] How complex is the current database?
- [ ] Are there multilingual requirements?
- [ ] Are there SEO-sensitive legacy URLs that must remain unchanged?
- [ ] Are there user accounts / authentication flows?
- [ ] Are there external integrations (mail, payments, APIs)?
- [ ] Which part of the site changes most often?
- [ ] Which part is the highest business priority?
- [ ] Which part is safest to migrate first?