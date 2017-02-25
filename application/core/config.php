<?php

define('COMISSION_AGENT', 'Comission Agent');

// Order Item Status
define('DRAFT', 1);
define('HOLD', 2);
define('SENT_TO_LOSIGT', 3);
define('DRAFT_FOR_SUPPLIER', 4);
define('CONFIRMED_BY_SUPPLIER', 5);
define('ON_THE_WAY', 8);
define('ON_STOCK', 9);
define('EXPECTS_ISSUE', 10);

// Roles
define('ROLE_ADMIN', 1);
define('ROLE_SALES_MANAGER', 2);
define('ROLE_ACCOUNTANT', 3);
define('ROLE_WAREHOUSE', 4);

define('ADMIN_PERM', 10);
define('SALES_MANAGER_PERM', 8);
define('ACCOUNTANT_PERM', 6);
define('WAREHOUSE_PERM', 4);

define('SESSION_SIDEBAR', 'sidebar_is_opened');

define('RESERVE_PERIOD', 7);

define('MANAGER_MAX_DISCOUNT_RATE_INPUT', 10);
define('MANAGER_MAX_REDUCED_PRICE_INPUT', 10);
define('MANAGER_MAX_SELL_VALUE_INPUT', 10);
define('MANAGER_MAX_COMMISSION_RATE_INPUT', 10);
define('MANAGER_MAX_COMMISSION_AGENT_BONUS_INPUT', 10);