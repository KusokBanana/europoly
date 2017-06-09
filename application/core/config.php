<?php

// Order Item Status
define('DRAFT', 1);
define('HOLD', 2);
define('SENT_TO_LOSIGT', 3);
define('DRAFT_FOR_SUPPLIER', 4);
define('CONFIRMED_BY_SUPPLIER', 5);
define('ON_THE_WAY', 8);
define('ON_STOCK', 9);
define('EXPECTS_ISSUE', 10);
define('ISSUED', 11);
define('RETURNED', 12);

// Roles
define('ROLE_ADMIN', 1);
define('ROLE_SALES_MANAGER', 2);
define('ROLE_ACCOUNTANT', 3);
define('ROLE_WAREHOUSE', 4);
define('ROLE_OPERATING_MANAGER', 5);

define('ADMIN_PERM', 10);
define('SALES_MANAGER_PERM', 8);
define('ACCOUNTANT_PERM', 6);
define('WAREHOUSE_PERM', 4);
define('OPERATING_MANAGER_PERM', 9);

define('SESSION_SIDEBAR', 'sidebar_is_opened');

define('RESERVE_PERIOD', 7);

define('MANAGER_MAX_DISCOUNT_RATE_INPUT', 10);
define('MANAGER_MAX_REDUCED_PRICE_INPUT', 10);
define('MANAGER_MAX_SELL_VALUE_INPUT', 10);
define('MANAGER_MAX_COMMISSION_RATE_INPUT', 10);
define('MANAGER_MAX_COMMISSION_AGENT_BONUS_INPUT', 10);

define('CLIENT_TYPE_END_CUSTOMER', 'End-Customer');
define('CLIENT_TYPE_DEALER', 'Dealer');
define('CLIENT_TYPE_COMISSION_AGENT', 'Comission Agent');

define('PAYMENT_CATEGORY_CLIENT', 'Client');
define('PAYMENT_CATEGORY_COMMISSION_AGENT', 'Comission Agent');
define('PAYMENT_CATEGORY_SUPPLIER', 'Supplier');
define('PAYMENT_CATEGORY_CUSTOMS', 'Customs');
define('PAYMENT_CATEGORY_DELIVERY', 'Delivery');
define('PAYMENT_CATEGORY_OTHER', 'Other');
define('PAYMENT_DIRECTION_INCOME', 'Income');
define('PAYMENT_DIRECTION_EXPENSE', 'Expense');

// Logs Names
define('LOG_ADD_TO_WAREHOUSE', 'New products added in warehouse');
define('LOG_DELIVERY_TO_WAREHOUSE', 'New products delivered to warehouse');
define('LOG_RETURN_TO_WAREHOUSE', 'Product was returned to warehouse');
define('LOG_ISSUE_FROM_WAREHOUSE', 'Products was issued from warehouse');
define('LOG_DISCARD_FROM_WAREHOUSE', 'Products was discarded from warehouse');
define('LOG_ASSEMBLING_PRODUCT_WAREHOUSE', 'Product was assembled in warehouse');
define('LOG_CHANGE_WAREHOUSE', 'Product changed warehouse');

define("AVENA_INN", '7715860159');
