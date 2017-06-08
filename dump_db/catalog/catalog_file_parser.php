<?php

set_time_limit(0);
$productsGlobal = [];
for ($i=1; $i < 4; $i++) {

    $file = dirname(__FILE__) . "/../controllers/parse$i.txt";

    $products = file_get_contents($file, FILE_USE_INCLUDE_PATH);

    $products = json_decode($products, true);

    $productsGlobal = array_merge($productsGlobal, $products);

}

echo '<pre>';
print_r($productsGlobal);
echo '</pre>';
//        die();

foreach ($productsGlobal as $key => $oneProduct) {
    $product_id = $key + 1;
    $weight = (float) ($oneProduct['weight']['val']);
    $purchase_price = (float) ($oneProduct['purchase_price']['val']);
    $sell_price = (double) ($oneProduct['sell_price']['val']);
    $visual_name = trim($oneProduct['visual_name']['val']);
    $visual_name_rus = trim($oneProduct['visual_name_rus']['val']);
    $visual_name = addslashes($visual_name);
    $visual_name = htmlspecialchars($visual_name);
    $visual_name = strip_tags($visual_name);
    $visual_name_rus = addslashes($visual_name_rus);
    $visual_name_rus = htmlspecialchars($visual_name_rus);
    $visual_name_rus = strip_tags($visual_name_rus);
    $set = ($weight && $weight !== null && $weight !== 'null' ?
            "weight = " . $weight . ", " : '') .
        ($purchase_price && $purchase_price !== null && $purchase_price !== 'null' ?
            "purchase_price = " . $purchase_price . ", " : '') .
        ($sell_price && $sell_price !== null && $sell_price !== 'null' ?
            "sell_price = " . $sell_price . ", " : '') .
        ($visual_name && $visual_name !== null && $visual_name !== 'null' ?
            "visual_name = '" . $visual_name . "', " : '');

    if ($set) {
        $update = "UPDATE products SET $set change_time = NOW() WHERE product_id = $product_id";
        echo '<br>' . $update;
        $this->update($update);
    }

    if ($visual_name_rus && $visual_name_rus !== null && $visual_name_rus !== 'null') {
        $update = "UPDATE nls_products SET visual_name = '$visual_name_rus' WHERE product_id = $product_id";
        echo '<br>' . $update;
        $this->update($update);
    }
}

echo '<br> Job is Done, My Master!';
die();