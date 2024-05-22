<?php

/**
 * Plugin Name: Kronos - DPD shipping gateway for WooCommerce
 * text-domain: kronos-dpd-shipping
 */

add_action('woocommerce_shipping_init', 'kronos_dhl_shipping_gateway_init');

function kronos_dhl_shipping_gateway_init()
{
    if (!class_exists('Kronos_DHL_Gateway')) {
        class Kronos_DHL_Gateway extends WC_Shipping_Flat_Rate
        {

            public function __construct($instance_id = 0)
            {
                $this->id                 = 'dpd_pickup_store';
                $this->instance_id        = absint($instance_id);
                $this->method_title       = __('DPD', 'kronos-dpd-shipping');
                $this->method_description = __('Livraison et points relais DPD', 'kronos-dpd-shipping');
                $this->supports           = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );
                $this->init();

                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            public function init()
            {
                $this->instance_form_fields = include __DIR__ . '/dpd-settings.php';
                $this->title                = $this->get_option('title');
                $this->tax_status           = $this->get_option('tax_status');
                $this->cost                 = $this->get_option('cost');
                $this->type                 = $this->get_option('type', 'class');
            }

            public function calculate_shipping($package = array())
            {
                $rate = array(
                    'id'      => $this->get_rate_id(),
                    'label'   => $this->title,
                    'cost'    => 0,
                    'package' => $package,
                );

                // Calculate the costs.
                $has_costs = false; // True when a cost is set. False if all costs are blank strings.
                $cost      = $this->get_option('cost');

                if ('' !== $cost) {
                    $has_costs    = true;
                    $rate['cost'] = $this->evaluate_cost(
                        $cost,
                        array(
                            'qty'  => $this->get_package_item_qty($package),
                            'cost' => $package['contents_cost'],
                        )
                    );
                }

                // Add shipping class costs.
                $shipping_classes = WC()->shipping()->get_shipping_classes();

                if (!empty($shipping_classes)) {
                    $found_shipping_classes = $this->find_shipping_classes($package);
                    $highest_class_cost     = 0;

                    foreach ($found_shipping_classes as $shipping_class => $products) {
                        // Also handles BW compatibility when slugs were used instead of ids.
                        $shipping_class_term = get_term_by('slug', $shipping_class, 'product_shipping_class');
                        $class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option('class_cost_' . $shipping_class_term->term_id, $this->get_option('class_cost_' . $shipping_class, '')) : $this->get_option('no_class_cost', '');

                        if ('' === $class_cost_string) {
                            continue;
                        }

                        $has_costs  = true;
                        $class_cost = $this->evaluate_cost(
                            $class_cost_string,
                            array(
                                'qty'  => array_sum(wp_list_pluck($products, 'quantity')),
                                'cost' => array_sum(wp_list_pluck($products, 'line_total')),
                            )
                        );

                        if ('class' === $this->type) {
                            $rate['cost'] += $class_cost;
                        } else {
                            $highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
                        }
                    }

                    if ('order' === $this->type && $highest_class_cost) {
                        $rate['cost'] += $highest_class_cost;
                    }
                }

                if ($has_costs) {
                    $this->add_rate($rate);
                }
            }
        }
    }
}

add_filter('woocommerce_shipping_methods', 'kronos_dhl_add_to_shipping_gateway');
function kronos_dhl_add_to_shipping_gateway($methods)
{
    $methods['dpd_pickup_store'] = new Kronos_DHL_Gateway();
    return $methods;
}
