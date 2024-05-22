<?php

/**
 * Plugin Name: Kronos - Monetico CIC gateway for WooCommerce
 * text-domain: kronos-monetico-cic-gateway
 */

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;

add_action('plugins_loaded', 'kronos_monetico_cic_payment_init', 11);
function kronos_monetico_cic_payment_init()
{
    if (class_exists('WC_Payment_Gateway')) {
        class Kronos_Monetico_CIC_Gateway extends WC_Payment_Gateway
        {
            public function __construct()
            {
                $this->id   = 'cb_monetico_cic';
                $this->has_fields = false;
                $this->method_title = __('Monetico (CIC)', 'kronos-monetico-cic-gateway');
                $this->method_description = __('Implémente les paiements par carte bancaire pour les commerçants qui ont un contrat Monetico (CIC, Crédit Mutuel)', 'kronos-monetico-cic-gateway');

                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');

                $this->init_form_fields();
                $this->init_settings();

                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            }

            public function init_form_fields()
            {
                $this->form_fields = apply_filters('kronos_monetico_cic_payment_pay_fields', array(
                    'enabled' => array(
                        'title' => __('Activer / Désactiver', 'kronos-monetico-cic-gateway'),
                        'type' => 'checkbox',
                        'label' => __('Activer Monetico CIC', 'kronos-monetico-cic-gateway'),
                        'default' => 'no'
                    ),
                    'sandbox' => array(
                        'title' => __('Environnement de test', 'kronos-monetico-cic-gateway'),
                        'type' => 'checkbox',
                        'label' => __('Activer le mode sandbox', 'kronos-monetico-cic-gateway'),
                        'default' => 'yes'
                    ),
                    'tpe' => array(
                        'title' => __('TPE', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                    ),
                    'societe' => array(
                        'title' => __('Code société', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                    ),
                    'secret_key' => array(
                        'title' => __('Clé secrète', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                    ),
                    'url_retour_ok' => array(
                        'title' => __('URL de retour OK', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                    ),
                    'url_retour_err' => array(
                        'title' => __('URL de retour KO', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                    ),
                    'lgue' => array(
                        'title' => __('Langue', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                        'default' => __('FR', 'kronos-monetico-cic-gateway'),
                    ),
                    'version' => array(
                        'title' => __('Version', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                        'default' => __('3.0', 'kronos-monetico-cic-gateway'),
                    ),
                    'title' => array(
                        'title' => __('Titre de la méthode de paiement', 'kronos-monetico-cic-gateway'),
                        'type' => 'text',
                        'default' => __('CB, Visa, Mastercard', 'kronos-monetico-cic-gateway'),
                    ),
                    'description' => array(
                        'title' => __('Description de la méthode de paiement', 'kronos-monetico-cic-gateway'),
                        'type' => 'textarea',
                        'default' => __('Paiement sécurisé par carte bancaire.', 'kronos-monetico-cic-gateway'),
                    ),
                ));
            }
        }
    }
}

//? Add Monetico CIC payment gateway to payment methods
add_filter('woocommerce_payment_gateways', 'kronos_monetico_cic_payment_add_to_payment_gateway');
function kronos_monetico_cic_payment_add_to_payment_gateway($gateways)
{
    $gateways[] = 'Kronos_Monetico_CIC_Gateway';
    return $gateways;
}

//? Register payment endpoint
add_action('rest_api_init', function () {
    register_rest_route(
        'kronos',
        '/monetico/payment',
        array(
            'methods'  => 'POST',
            'callback' => 'kronos_monetico_cic_prepare_payment_endpoint'
        )
    );
});

function kronos_monetico_cic_prepare_payment_endpoint($request)
{
    $order = wc_get_order($request['order_id']);
    $payment_gateway = wc_get_payment_gateway_by_order($order);

    $url = $payment_gateway->get_option('sandbox') == 'yes' ? 'https://p.monetico-services.com/test/paiement.cgi' : 'https://p.monetico-services.com/paiement.cgi';
    $tpe = $payment_gateway->get_option('tpe');
    $lgue = $payment_gateway->get_option('lgue');
    $societe = $payment_gateway->get_option('societe');
    $version = $payment_gateway->get_option('version');
    $url_retour_err = $payment_gateway->get_option('url_retour_err');
    $url_retour_ok = $payment_gateway->get_option('url_retour_ok');
    $secret_key = pack('H*', $payment_gateway->get_option('secret_key'));
    $algo = 'sha1';

    $date = date_format($order->get_date_created(), 'd/m/Y:H:i:s');
    $montant = $order->get_total() . 'EUR';
    $reference = $order->get_id();

    $addresses = [
        'billing' => [
            'addressLine1' => $order->get_billing_address_1(),
            'city' => $order->get_billing_city(),
            'postalCode' => $order->get_billing_postcode(),
            'country' => $order->get_billing_country(),
        ],
        'shipping' => [
            'addressLine1' => $order->get_shipping_address_1(),
            'city' => $order->get_shipping_city(),
            'postalCode' => $order->get_shipping_postcode(),
            'country' => $order->get_shipping_country(),
        ],
    ];

    $contexte_commande = base64_encode(json_encode($addresses));

    $key = "TPE=$tpe*contexte_commande=$contexte_commande*date=$date*lgue=$lgue*montant=$montant*reference=$reference*societe=$societe";

    if ($url_retour_err) {
        $key = $key .= "*url_retour_err=$url_retour_err";
    }
    if ($url_retour_ok) {
        $key = $key .= "*url_retour_ok=$url_retour_ok";
    }
    $key = $key .= "*version=$version";

    $mac = strtoupper(hash_hmac($algo, $key, $secret_key));

    return wp_send_json([
        'method' => $order->get_payment_method(),
        'action' => $url,
        'fields' => [
            'TPE' => $tpe,
            'version' => $version,
            'date' => $date,
            'montant' => $montant,
            'reference' => $reference,
            'lgue' => $lgue,
            'MAC' => $mac,
            'contexte_commande' => $contexte_commande,
            'societe' => $societe,
            'url_retour_ok' => $url_retour_ok,
            'url_retour_err' => $url_retour_err,
        ],
    ]);
}

//? Register payment confirmation endpoint
add_action('rest_api_init', function () {
    register_rest_route(
        'kronos',
        '/monetico/payment/confirm',
        array(
            'methods'  => 'POST',
            'callback' => 'kronos_monetico_cic_prepare_payment_confirm_endpoint'
        )
    );
});

function kronos_monetico_cic_prepare_payment_confirm_endpoint($request)
{
    $order = wc_get_order($request['reference']);
    $payment_gateway = wc_get_payment_gateway_by_order($order);
    $secret_key = pack('H*', $payment_gateway->get_option('secret_key'));
    $algo = 'sha1';

    $params = $request->get_body_params();
    unset($params['MAC']);
    ksort($params);

    $key = '';
    foreach ($params as $param_key => $param_value) {
        $key .= $key ? "*$param_key=$param_value" : "$param_key=$param_value";
    }

    $mac = strtoupper(hash_hmac($algo, $key, $secret_key));

    // Check the order reference parameter
    if ($request && $request['reference']) {
        // Check if payment success
        if ($request['MAC'] == $mac) {
            //* If payment success
            if ($request['code-retour'] == 'payetest' || $request['code-retour'] == 'paiement') {
                $order->set_status('processing');
                $order->is_paid();
                $order->save();
                $result = "version=2\ncdr=0\n";
            }
            //! If payment failed
            else {
                $order->set_status('failed');
                $order->save();
                $result = "version=2\ncdr=1\n";
            }
        }
    }

    //! DO NOT REMOVE `echo` BELOW, this ensure the right response formatting
    echo $result;
    return;
}
