## Code documentation

The following folders have been created in the codebase:

- `migrations` - contains migration files for database tables
- `src/Account` - contains files related to user account management
- `src/Dashboard` - contains files related to dashboard management
- `src/Kernel` - contains files related to the application kernel
- `tests` - contains unit test files

> **How to Add a New Service to DI?**
>
> In the `src/Kernel/Container/ContainerServicesManager.php` file, add a new service to the DI container in the `registerServices` method.

> **How to Execute Migrations Up?**
> 
> Run make migrate-up

> **How to Execute Migrations Down?**
>
> Run make migrate-down

> **How to Create a Test User?**
>
> Run make create-test-user

