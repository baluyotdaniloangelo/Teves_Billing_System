<?php

return [
    // Existing category: Kitchen Tools & Equipment. Product IDs and categories are retained.
    'kitchen_category_ids' => [4],
    // Kitchen inventory uses these grouped products plus the newer Shine stove items.
    // The category also contains unrelated legacy products, so category alone is too broad.
    'kitchen_main_product_ids' => [3, 4, 5, 6, 7],
    'kitchen_product_ids' => [214, 237, 238, 239],
    'empty_container_category_ids' => [2], // Existing LPG category.
    // Existing COMPACT VALVE - REGULATOR product. Add regulator variants here,
    // or a regulator-only main product ID below; never classify by a name substring.
    'regulator_product_ids' => [218],
    'regulator_main_product_ids' => [],
    'regulator_inventory_details_enabled' => false,
    'show_unclassified_sales' => false,
];
