<?php
#!/usr/bin/env php
// * * * * * /usr/bin/php -f /home/u470791/lk.evropoly.com/www/cron/monthly_payment.php &> /dev/null
require dirname(__FILE__) . '/../application/core/model.php';
require dirname(__FILE__) . '/../application/models/model_accountant.php';
$model = new ModelAccountant();
$model->checkMonthlyPayments();
