<?php

require_once ('Ebay.php');

//some real data
$data = [];

//creating new Item obj
$item = new \App\API\Ebay($data);

//configure an url options
$item->setEndPoint('/inventory/v1/inventory_item/');
$item->setSku('good_22');

//check if it works good
if ($item->createOrReplaceInventoryItem()) {
    echo "Good is now uploaded";
} else {
    echo "There are some mistakes, check item options";
}
