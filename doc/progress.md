Progress
========
* 2021-01-12 Merged changes from main branch
* 2021-01-13 Picked fixes in navigation from main branch 
* 2021-01-12 User Registrar_id no longer UNIQE, Added App\Domain\Account, Refactored Inviation and Registration
* 2021-01-10 Added Create Account (after Invitation)
* 2021-01-09 Added Accept Invitation and Accept Invitation Form (create account still remaining)
* 2021-01-08 Added Create Invitation and QR Code
* 2021-01-07 Added Invitation Switch
* 2021-01-06 Self registration switchable in .env. Add registrar to the User entity
* 2021-01-05 Added simple self registration form
* 2021-01-02 Added Events to both workflows
* 2021-01-01 Added Role Restriction to symfony workflow, Added identifier in ItemList and StuffList. Fixed PHPStan Problem with PHPUnit. Added howto-demo.md.
* 2020-12-27 Implemented access control on transitions
* 2020-12-27 Implemented Language switching an extended user locale
* 2020-12-26 Introduced WorkflowEntityInterface and Transfer Service
* 2020-12-26 Added translations in controllers. Check existance of item in edit user and edit role. Some refactoring.
* 2020-12-26 Added role add and remove for Transitions
* 2020-12-25 Added create and edit for Transitions
* 2020-12-24 Moved onEnter and onLeave from Transition to State. Added Workflows create and edit. Added create and edit for State.
* 2020-12-22 Added custom Workflow, State and Transition with listAction and showAction
* 2020-12-21 Basic custom workflow ready. Added workflow SVG Images for item.
* 2020-12-17 Added breadcrumb navigation in item list
* 2020-12-17 Merged translations from main branch
* 2020-12-16 Added Item and example workflow
* 2020-12-15 Completed translation of templates in de/en
* 2020-12-14 Started translation of templates in de/en
* 2020-12-12 Search form for users 
* 2020-12-11 Improved UI (menu drop-down, user administration)
* 2020-12-10 Added impersonation
* 2020-12-09 Admin: attach/detach roles to/from user
* 2020-12-04 Added delete confirmation modal for user and role. Changed Access Control by Roles
* 2020-11-30 Admin Role and User create and edit finished. Removed all Code Style notifications
* 2020-11-29 Added check script, mess detector, PHPStan and PHPUnit for code quality. Added LifecycleCallbacks
* 2020-11-23 Admin User and Role show pages. Started Admin User and Role create
* 2020-11-22 3 static pages, Admin Pages for Users and Roles Lists. Entities now timestampable
* 2020-11-21 Login/Logout finished, changed Fixtures, to have an Admin User for the start
* 2020-11-20 Added Bootstrap, Fontawesome and started login via formAuthenticator
* 2020-11-19 Started README, added Logger, added ORM, User and Roles Entities
* 2020-11-18 Created Symfony 5.1 scaffold with Routing and TWIG
