
Auth::user()->departments; 
USERS ROLES ON PETTY CASH AND ADVANCE PURCHASE

how to manage roles in a system guid
-composer require spatie/laravel-permission
first you should install package

then you run this command = php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
which generate the following table
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions

Usertype | Role
0 - SuperUser 



