<?php
if( !function_exists('is_access_direct') ||
    is_access_direct() )
    exit(NO_DIRECT_ACCESS);

if( empty($supplied_data) ) {
    throw new procException("Interworker is missing required data");
} else {
    list($item_id, $item_type, $item_table) = $supplied_data;
}

$input_entity_conditions = [
    'unique_code' => ['<>', 'NULL', '']
];

#DDC parameters
$entity_tables_fields = [
    $item_table => [
        'id', 'unique_code', 'date_created', 'brand', 'model_name', 'r', 'w', 'h', 'load_rate', 'speed', 'extra', 'season', 'stock', 'retail', 'dealer', 'promo', 'is_top', 'promotion_id', 'views', 'description', 'rating_score', 'rating_votes'
    ]
];

$entity_conditions = [
    'unique_code' => ['<>', 'NULL', ''],
    'id'          => ['=', $item_id, 'AND']
];

$entity_orders = ['unique_code' => 'ASC'];

$entity_limits = [1];

$options = ['group' => ['brand', 'model_name']];
#END\DDC parameters

if( !$_BOOT->involve_object("DB_Handler")->validate_tables(array_keys($entity_tables_fields)) ) {
    throw new procException("Table(s) does not exists");
}

$tyre['item'] = $_BOOT->involve_object(
    "DB_CellConstructor",
    [$_BOOT->involve_object("DB_Handler")]
)->dynamic_data_cell(NULL, $entity_tables_fields, $entity_conditions, $entity_orders, $entity_limits, $options)[0];

#DDC parameters
$entity_conditions = [
    'unique_code' => ['<>', 'NULL', ''],
    'brand'       => ['=', $tyre['item']['brand'], 'AND'],
    'model_name'  => ['=', $tyre['item']['model_name'], 'AND']
];
#END\DDC parameters

$tyre['modifications'] = $_BOOT->involve_object(
    "DB_CellConstructor",
    [$_BOOT->involve_object("DB_Handler")]
)->dynamic_data_cell(NULL, $entity_tables_fields, $entity_conditions, $entity_orders, NULL);

#DDC parameters description
$entity_tables_fields = [
    'skladkoles_items_descriptions' => [
        'id', 'unique_code', 'type', 'description'
    ]
];

$entity_conditions = [
    'unique_code' => ['=', $tyre['item']['unique_code'], ''],
];

$entity_limits = [1];
#END\DDC parameters description

$tyre['description'] = $_BOOT->involve_object(
    "DB_CellConstructor",
    [$_BOOT->involve_object("DB_Handler")]
)->dynamic_data_cell(NULL, $entity_tables_fields, $entity_conditions, NULL, $entity_limits)[0];

return $tyre;
?>
