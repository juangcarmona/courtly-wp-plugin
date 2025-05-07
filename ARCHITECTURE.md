# Courtly Plugin Architecture

This document outlines the architecture, design patterns, and folder structure used in the Courtly WordPress plugin.

## Folder Structure

```
courtly/
├── Application/            # Application logic (services, commands, queries)
│   └── Controllers/        # Application-level controllers
├── Domain/                 # Core business logic and entities
│   ├── Entities/           # Domain entities (e.g., Court, OpeningHours)
│   ├── Repositories/       # Repository interfaces
│   └── Services/           # Domain services (e.g., CourtReservationService)
├── Infrastructure/         # Database repositories and setup
│   ├── Repositories/       # Repository implementations
│   ├── Public/             # Public routing and logic
│   └── CourtlyContainer.php # Dependency container
├── Presentation/           # UI logic, separated by context
│   ├── Admin/              # Admin-specific UI logic
│   │   ├── Pages/          # Entry points registered via WP admin_menu
│   │   └── Views/          # Views rendered from controllers
│   ├── Public/             # Public-facing UI logic
│   │   ├── Pages/          # Entry points for public pages
│   │   └── Views/          # Views rendered from controllers
│   └── Shared/             # Shared components (e.g., Logger, Calendar)
└── tests/                  # Unit and integration tests
```

## Design Patterns

### Layered Architecture
- **Domain Layer**: Core business logic, entities, and repository interfaces.
- **Application Layer**: Orchestrates domain logic via services and controllers.
- **Infrastructure Layer**: Implements repositories and external dependencies.
- **Presentation Layer**: Handles UI and user interaction.

### Dependency Injection
- Dependencies are injected via constructors.
- The `ControllerFactory` centralizes controller instantiation and ensures all dependencies are properly injected.

### Repository Pattern
- Repositories abstract data access, providing a consistent interface for querying and persisting entities.

## Presentation Layer

The Presentation layer handles rendering both admin and public-facing UIs. It is divided into:

- `Presentation/Admin/Pages`: PHP entry points for admin pages (e.g. registered via `add_menu_page`).
- `Presentation/Public/Pages`: Entry points for public pages, usually backed by shortcodes or custom routing.
- `Views`: Each Page loads a View file, and passes it data from its controller.

### Page–Controller–View Flow

Each screen in Courtly is composed of three distinct components:

1. **Page file** (`Pages/*.php`): Acts as the entry point registered with WordPress. It delegates logic to the controller.
2. **Controller** (`Application/Controllers/*.php`): Handles `POST` logic (`handlePost()`), permissions, and prepares data (`getViewData()`).
3. **View** (`Views/*.php`): Renders HTML using data provided by the controller. It contains no business logic.

Example page flow:

```php
// Pages/OpeningHoursPage.php
$controller = ControllerFactory::make(AdminOpeningHoursController::class);
$controller->handlePost();
$data = $controller->getViewData();

include __DIR__ . '/../Views/OpeningHoursView.php';
```

This pattern:
- Keeps controllers testable and reusable
- Keeps views presentation-only
- Keeps WordPress-specific routing logic isolated in Page files

## Testing Guidelines

- **Unit Tests**: Located in `tests/Unit`
- **Integration Tests**: Located in `tests/Integration`
- Use mocks for dependencies in unit tests.

## Conventions

- **Services**: Placed in `Domain/Services`.
- **Controllers**: Placed in `Application/Controllers`.
- **Repositories**:
  - Interfaces in `Domain/Repositories`
  - Implementations in `Infrastructure/Repositories`
- **Views**: Placed in `Presentation/Admin/Views` or `Presentation/Public/Views`.
- **Pages**: Entry points that call controller methods and load views. Located in `Pages/` folders within the admin or public sections.

## Notes

- Follow the existing patterns for consistency.
- Use the `ControllerFactory` and `CourtlyContainer` for dependency management.
- The `ControllerFactory` ensures all controllers are instantiated with their required dependencies.
