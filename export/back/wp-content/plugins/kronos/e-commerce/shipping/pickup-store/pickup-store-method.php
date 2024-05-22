<?php

/**
 * Plugin Name: Kronos - Pickup Store shipping gateway for WooCommerce
 * text-domain: kronos-pickup-store-shipping
 */

add_action('woocommerce_shipping_init', 'kronos_pickup_store_shipping_gateway_init');

function kronos_pickup_store_shipping_gateway_init()
{
    if (!class_exists('Kronos_Pickup_Store_Gateway')) {
        class Kronos_Pickup_Store_Gateway extends WC_Shipping_Local_Pickup
        {

            public function __construct($instance_id = 0)
            {
                $this->id                 = 'pickup_store';
                $this->instance_id        = absint($instance_id);
                $this->method_title       = __('Retrait en magasin', 'kronos-pickup-store-shipping');
                $this->method_description = __('Livraison en magasin', 'kronos-pickup-store-shipping');
                $this->supports           = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );
                $this->init();
            }

            public function init()
            {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->title                = $this->get_option('title');
                $this->stores                = $this->get_option('stores');
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            public function init_form_fields()
            {
                $this->init_settings();
                $this->instance_form_fields = array(
                    'title'      => array(
                        'title'       => __('Method title', 'woocommerce'),
                        'type'        => 'text',
                        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                        'default'     => __('Retrait en magasin', 'kronos-pickup-store-shipping'),
                        'desc_tip'    => true,
                    ),
                    'stores'    => array(
                        'title'             => __('Magasins', 'kronos-pickup-store-shipping'),
                        'type'              => 'multiselect',
                        'class'             => 'wc-enhanced-select',
                        'css'               => 'width: 400px;',
                        'default'           => '',
                        'options'           => $this->get_stores(),
                        'custom_attributes' => array(
                            'data-placeholder' => __('Sélectionnez un magasin', 'kronos-pickup-store-shipping'),
                        ),
                    ),
                );
            }

            public function get_stores()
            {
                $args = array(
                    'numberposts' => 10,
                    'post_type'   => 'pickup_stores',
                    'order'       => 'ASC',
                    'orderby'     => 'title'
                );

                $stores = [];
                foreach (get_posts($args) as &$store) {
                    $stores[$store->ID] = $store->post_title;
                }

                return $stores;
            }

            public function calculate_shipping($package)
            {
                $this->add_rate(
                    array(
                        'label'   => $this->title,
                        'package' => $package,
                        'cost'    => 0,
                    )
                );
            }
        }
    }
}

add_filter('woocommerce_shipping_methods', 'kronos_pickup_store_add_to_shipping_gateway');
function kronos_pickup_store_add_to_shipping_gateway($methods)
{
    $methods['pickup_store'] = new Kronos_Pickup_Store_Gateway();
    return $methods;
}
