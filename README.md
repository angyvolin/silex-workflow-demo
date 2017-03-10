silex-workflow-demo
===================

Installation
------------

1. Change db.user parameter in 'db.options' section src/app.php to your actual db user:

    ```
    'db.options' => array(
        'user' => 'your_db_user',
        // ...
    )
    ```

2. Execute:

    ```
    composer install
    bin/console orm:schema-tool:create
    ```

Run
---
```
composer run
```
