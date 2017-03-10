silex-workflow-demo
===================

Installation
------------

1. Change db.user parameter in 'db.options' section src/app.php to your actual db user, eg:


```
'db.options' => array(
    'user' => 'your_db_user',
    // ...
)
```

2. :

```
composer install
bin/console orm:schema-tool:create
bin/console orm:schema-tool:update --force
```

Run
---
```
composer run
```