<?php
if( !function_exists('is_access_direct') ||
    is_access_direct() )
    exit(NO_DIRECT_ACCESS);

$limit = ( !empty($supplied_data['limit']) )
    ? $supplied_data['limit']
    : 10
;

$object_db_handler   = $_BOOT->involve_object("DB_Handler");
$object_catalogCells = $_BOOT->assign_namespace($C_N::MEAT_SPECIFIC)->involve_object("CatalogCells", [$object_db_handler]);

$items_combined = $object_catalogCells->popular_items_data_cell($limit);

return ( $_BOOT->involve_object("DataThralls")->is_filled_array($items_combined) ) ? $items_combined : [];
?>
