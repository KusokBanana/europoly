<?php
#!/usr/bin/env php
// * * * * * /usr/bin/php -f /home/u470791/lk.evropoly.com/www/cron &> /dev/null
require dirname(__FILE__) . '/../application/models/model_payment.php';
$model = new ModelPayment();
$model->updateDatabaseCurrency();
