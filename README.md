# Permission PSR-7 middleware

PSR-7 permission middleware. Sets the status code to 401 if needed according to user defined rules.

## ALLOW mode
The default allow rule set allows all connections through unless otherwise stated.

## DENY mode
A default deny rule set will deny all connections through  unless a url matches a specific rule.

## usage

```php
use Prezto\Mode;
use Prezto\PermissionMiddleware;

$permissionMiddleware = new PermissionMiddleware(['/admin(.*)', '/login'], Mode::DENY);
$permissionMiddleware = new PermissionMiddleware(['/admin(.*)', '/login'], Mode::ALLOW)
```
