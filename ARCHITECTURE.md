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
│   ├── Public/             # Public-facing UI logic
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

## Testing Guidelines
- **Unit Tests**: Located in `tests/Unit`.
- **Integration Tests**: Located in `tests/Integration`.
- Use mocks for dependencies in unit tests.

## Conventions
- **Services**: Placed in `Domain/Services`.
- **Controllers**: Placed in `Application/Controllers`.
- **Repositories**: Interfaces in `Domain/Repositories`, implementations in `Infrastructure/Repositories`.
- **Views**: Placed in `Presentation/Admin/Views` or `Presentation/Public/Views`.

## Notes
- Follow the existing patterns for consistency.
- Use the `ControllerFactory` and `CourtlyContainer` for dependency management.
- The `ControllerFactory` is a core component that ensures all controllers are instantiated with their required dependencies.