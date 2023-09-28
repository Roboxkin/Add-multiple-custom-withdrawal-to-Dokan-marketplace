//Register withdraw method Russian bank "World"
function register_world_bank_card_withdraw_method($world_bank_card){
    $world_bank_card['world_bank_card']    = [
        'title'     => __( 'World bank card', 'dokan' ),
        'callback'  => 'dokan_withdraw_method_world_bank_card'
    ];

    return $world_bank_card;
}
add_filter( 'dokan_withdraw_methods', 'register_world_bank_card_withdraw_method', 99 );

//Register withdraw method Russian bank "Tinkoff"
function register_tinkoff_bank_card_withdraw_method($tinkoff_bank_card){
	$tinkoff_bank_card['tinkoff_bank_card']    = [
        'title'     => __( 'Tinkoff bank card', 'dokan' ),
        'callback'  => 'dokan_withdraw_method_tinkoff_bank_card'
    ];

    return $tinkoff_bank_card;
}
add_filter( 'dokan_withdraw_methods', 'register_tinkoff_bank_card_withdraw_method', 99 );

//Method world bank card Callback Function
function dokan_withdraw_method_world_bank_card( $store_settings ){
    $value = isset( $store_settings['payment']['world_bank_card']['value'] ) ? esc_attr( $store_settings['payment']['world_bank_card']['value'] ) : ''; ?>

    <div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e('Card', 'dokan-lite'); ?></span>
                <input value="<?php echo esc_attr($value); ?>" name="settings[world_bank_card][value]" class="dokan-form-control value" placeholder="1111-2222-3333-4444" type="text">
            </div>
        </div>
    </div>
	<div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e('Phone', 'dokan-lite'); ?></span>
                <input value="<?php echo esc_attr($value); ?>" name="settings[Phone][value]" class="dokan-form-control value" placeholder="+7(978)000-00-00" type="text">
            </div>
        </div>
    </div>
    <?php if (dokan_is_seller_dashboard()):?>
        <div class="dokan-form-group">
            <div class="dokan-w8">
                <input name="dokan_update_payment_settings" type="hidden">
                <button class="ajax_prev disconnect dokan_payment_disconnect_btn dokan-btn dokan-btn-danger <?php echo empty($value) ? 'dokan-hide' : ''; ?>" type="button" name="settings[world_bank_card][disconnect]">
                    <?php esc_attr_e('Disconnect', 'dokan-lite'); ?>
                </button>
            </div>
        </div>
		<?php endif; ?>
    <?php
}

//tinkoff_bank_card
function dokan_withdraw_method_tinkoff_bank_card( $store_settings ){
    $value = isset( $store_settings['payment']['tinkoff_bank_card']['value'] ) ? esc_attr( $store_settings['payment']['tinkoff_bank_card']['value'] ) : ''; ?>

    <div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e('Card', 'dokan-lite'); ?></span>
                <input value="<?php echo esc_attr($value); ?>" name="settings[tinkoff_bank_card][value]" class="dokan-form-control value" placeholder="1111-2222-3333-4444" type="text">
            </div>
        </div>
    </div>
	<div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e('Phone', 'dokan-lite'); ?></span>
                <input value="<?php echo esc_attr($value); ?>" name="settings[Phone][value]" class="dokan-form-control value" placeholder="+7(978)000-00-00" type="text">
            </div>
        </div>
    </div>
    <?php if (dokan_is_seller_dashboard()):?>
        <div class="dokan-form-group">
            <div class="dokan-w8">
                <input name="dokan_update_payment_settings" type="hidden">
                <button class="ajax_prev disconnect dokan_payment_disconnect_btn dokan-btn dokan-btn-danger <?php echo empty($value) ? 'dokan-hide' : ''; ?>" type="button" name="settings[tinkoff_bank_card][disconnect]">
                    <?php esc_attr_e('Disconnect', 'dokan-lite'); ?>
                </button>
            </div>
        </div>
		<?php endif; ?>
    <?php 
}

//Save
function save_withdraw_method_wise( $store_id, $dokan_settings ) {

  if ( isset( $_POST['settings']['world_bank_card']['value'] ) ) {
      $dokan_settings['payment']['world_bank_card'] = array(
          'value' => sanitize_text_field( $_POST['settings']['world_bank_card']['value'] ),
      );
  }
  if ( isset( $_POST['settings']['tinkoff_bank_card']['value'] ) ) {
      $dokan_settings['payment']['tinkoff_bank_card'] = array(
          'value' => sanitize_text_field( $_POST['settings']['tinkoff_bank_card']['value'] ),
      );
  }

  update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
}
add_action( 'dokan_store_profile_saved', 'save_withdraw_method_wise', 10, 2 );

//Add Custom Withdraw Method to the Payment Method List
function add_custom_withdraw_in_payment_method_list( $required_fields, $payment_method_id ){
    if( 'world_bank_card' == $payment_method_id ){
        $required_fields = ['value'];
    }
	if( 'tinkoff_bank_card' == $payment_method_id ){
        $required_fields = ['value'];
    }
    return $required_fields;
}
add_filter( 'dokan_payment_settings_required_fields', 'add_custom_withdraw_in_payment_method_list', 10, 2 );

//Active Withdraw Method List
function custom_method_in_active_withdraw_method( $active_payment_methods, $vendor_id ) {
    $store_info = dokan_get_store_info( $vendor_id );
    if ( isset( $store_info['payment']['world_bank_card']['value'] ) && $store_info['payment']['world_bank_card']['value'] !== false ) {
        $active_payment_methods[] = 'world_bank_card';
    }
	if ( isset( $store_info['payment']['tinkoff_bank_card']['value'] ) && $store_info['payment']['tinkoff_bank_card']['value'] !== false ) {
        $active_payment_methods[] = 'tinkoff_bank_card';
    }
    return $active_payment_methods;
}
add_filter( 'dokan_get_seller_active_withdraw_methods', 'custom_method_in_active_withdraw_method', 99, 2 );

//Include Method to Available Withdraw Method Section
function world_bank_card_include_method_in_withdraw_method_section( $world_bank_card ){
    $world_bank_card[] = 'world_bank_card';
    return $world_bank_card;
}
add_filter( 'dokan_withdraw_withdrawable_payment_methods', 'world_bank_card_include_method_in_withdraw_method_section' );

function tinkoff_bank_card_include_method_in_withdraw_method_section( $tinkoff_bank_card ){
    $tinkoff_bank_card[] = 'tinkoff_bank_card';
    return $tinkoff_bank_card;
}
add_filter( 'dokan_withdraw_withdrawable_payment_methods', 'tinkoff_bank_card_include_method_in_withdraw_method_section' );

//Add details to the Withdrawal Requests
function vue_admin_withdraw(){
    ?>
    <script>
      var hooks;
      function getCustomPaymentDetails( details, method, data ){
        if ( data[method] !== undefined ) {
          if ( 'world_bank_card' === method) {
            details = data[method].value || '';
          }
		  if ( 'tinkoff_bank_card' === method) {
            details = data[method].value || '';
          }
        }
  
        return details;
      }
      dokan.hooks.addFilter( 'dokan_get_payment_details', 'getCustomPaymentDetails', getCustomPaymentDetails, 33, 3 );
    </script>
    <?php
}
add_action( 'admin_print_footer_scripts', 'vue_admin_withdraw', 99 );
