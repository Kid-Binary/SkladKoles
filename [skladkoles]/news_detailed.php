<?php
if( !function_exists('is_access_direct') ||
    is_access_direct() )
    exit(NO_DIRECT_ACCESS);

unset(
	$_SESSION['filter_parameters'], $_SESSION['pagination'], $_SESSION['search']
);

if( empty( $_AREA->{$C_E::_ARGUMENTS}[0] ) ) {
    return FALSE;
}

$db_handler = $_BOOT->involve_object('DB_Handler');

#AUTHORIZATION
if( $_BOOT->assign_namespace($C_N::MEAT_EXTERNAL)->involve_object("PHPLoginLink", [$db_handler, $_AREA->{$C_E::_REQUEST}])->is_user_logged_in() ) {
    define('WHITELIGHT', TRUE);
}
#END/AUTHORIZATION

$cart_items         = $_BOOT->assign_namespace($C_N::MEAT_SPECIFIC)->involve_object('Cart', [$db_handler])->obtain_cart_items_data();
$counted_cart_items = $_BOOT->assign_namespace($C_N::MEAT_SPECIFIC)->involve_object('Cart', [$db_handler])->count_cart_items($cart_items);

$interworkers = [
    'head'                   => $this->load_inter('worker', 'head'),
    'directories'            => $this->load_inter('worker', 'directories'),
    'news_single'            => $this->load_inter('worker', 'news', ['id' => $_AREA->{$C_E::_ARGUMENTS}[0]]),
    'news_recent'            => $this->load_inter('worker', 'news'),
    'contacts_working_hours' => $this->load_inter('worker', 'contacts_working_hours'),
    'contacts_addresses'     => $this->load_inter('worker', 'contacts_addresses'),
    'contacts_phones'        => $this->load_inter('worker', 'contacts_phones')
];

$interlayers = [
    'head'                        => $this->load_inter('layer', 'head',
                                        array_merge($interworkers['head'],['news_single' => $interworkers['news_single']])
                                     ),
    'credentials'                 => $this->load_inter('layer', 'credentials'),
    'logo'                        => $this->load_inter('layer', 'logo'),
    'directories'                 => $this->load_inter('layer', 'directories',
                                        $interworkers['directories']
                                     ),
    'search_widget'               => $this->load_inter('layer', 'search_widget'),
    'basket_widget'               => $this->load_inter('layer', 'basket_widget', $counted_cart_items),
    'basket_popup'                => $this->load_inter('layer', 'basket_popup', $cart_items),
    'news_single'                 => $this->load_inter('layer', 'news_single',
                                        $interworkers['news_single'][0]
                                     ),
    'news_recent'                 => $this->load_inter('layer', 'news_recent',
                                        $interworkers['news_recent']
                                     ),
    'footer_shop_information'     => $this->load_inter('layer', 'footer_shop_information',
                                        $interworkers['contacts_working_hours']
                                     ),
    'footer_shop_contacts'        => $this->load_inter('layer', 'footer_shop_contacts',
                                        [
                                            $interworkers['contacts_addresses'],
                                            $interworkers['contacts_phones']
                                        ]
                                     ),
    'footer_directories'          => $this->load_inter('layer', 'footer_directories',
                                        $interworkers['directories']
                                     ),
    'footer_developers'           => $this->load_inter('layer', 'footer_developers'),

    #DEM
    'dem_manager_widget'          => $this->load_inter('layer', 'dem_manager_widget'),

    #SEARCH
    'analyticstracking'           => $this->load_inter('layer', 'analyticstracking'),
    'yandexmetrika'               => $this->load_inter('layer', 'yandexmetrika')
];

require_once(BASEPATH . "/[{$_AREA->{$C_E::_SUBSYSTEM}}]/_structures/{$_AREA->{$C_E::_DIRECTORY}}_structure.php");
?>