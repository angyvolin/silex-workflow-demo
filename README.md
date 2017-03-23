# silex-workflow-demo [![SensioLabsInsight](https://insight.sensiolabs.com/projects/c7b71caa-7264-4e8b-85b2-879c675bde5f/mini.png)](https://insight.sensiolabs.com/projects/c7b71caa-7264-4e8b-85b2-879c675bde5f)

This demo application is based on [lyrixx/SFLive-Paris2016-Workflow](https://github.com/lyrixx/SFLive-Paris2016-Workflow)

![workflow](https://github.com/angyvolin/silex-workflow-demo/blob/master/web/img/workflow.png)

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
