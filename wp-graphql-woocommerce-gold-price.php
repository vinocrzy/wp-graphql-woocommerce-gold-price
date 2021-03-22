<?php

/**
 * Plugin Name: WPGraphQL Gold price Extension for woocommerce
 * Description:       Add WooCommerce Gold Prices support and functionality to your WPGraphQL server
 * Author:            Vino Crazy
 * Author URI:        https://vinocrazy.com/
 * Version:           1.0.0
 * Requires PHP:      7.0
 * GitHub Plugin URI: https://github.com/vinocrzy/wp-graphql-woocommerce-gold-price
 *
 * @package         WPGraphQL_WOO_Gold
 */


add_action('graphql_register_types', 'gold_price');

function gold_price()
{

    register_graphql_object_type('GoldPriceType', [
        'description' => __('Describe the Type and what it represents', 'wp-graphql-gold-price'),
        'fields' => [
            'gold24k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold24k', 'wp-graphql-gold-price'),
                'resolve'     => function ($source) {

                    return (float) $source['24k'];
                },
            ],
            'gold22k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold22k', 'wp-graphql-gold-price'),
                'resolve'     => function ($source) {
                    return (float) $source['22k'];
                },
            ],
            'gold18k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold18k', 'wp-graphql-gold-price'),
                'resolve'     => function ($source) {

                    return (float) $source['18k'];
                },
            ],
            'gold14k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold14k', 'wp-graphql-gold-price'),
                'resolve'     => function ($source) {

                    return (float) $source['14k'];
                },
            ],
        ],
    ]);

    register_graphql_fields('RootQuery', [
        'goldPriceList' => [
            'type' => 'GoldPriceType',
            'description' => __('Woocommerce Gold Price Options', 'wp-graphql-gold-price'),
            'resolve' => function () {
                $karats = get_option('woocommerce_gold_price_options');

                return $karats;
            }
        ]

    ]);

    register_graphql_mutation('setGoldPrice', [

        # inputFields expects an array of Fields to be used for inputting values to the mutation
        'inputFields'         => [
            'gold24k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold24k', 'wp-graphql-gold-price'),

            ],
            'gold22k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold22k', 'wp-graphql-gold-price'),

            ],
            'gold18k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold18k', 'wp-graphql-gold-price'),

            ],
            'gold14k' => [
                'type' => 'Float',
                'description' => __('Woocommerce Gold Price Options gold14k', 'wp-graphql-gold-price'),

            ],
        ],

        # outputFields expects an array of fields that can be asked for in response to the mutation
        # the resolve function is optional, but can be useful if the mutateAndPayload doesn't return an array
        # with the same key(s) as the outputFields
        'outputFields'        => [
            'gold24k' => [
                'type' => 'String',
                'description' => __('Description of the output field', 'your-textdomain'),
                'resolve' => function ($payload, $args, $context, $info) {
                    return isset($payload['out']) ? json_encode($payload['out'])  : null;
                }
            ]
        ],

        # mutateAndGetPayload expects a function, and the function gets passed the $input, $context, and $info
        # the function should return enough info for the outputFields to resolve with
        'mutateAndGetPayload' => function ($input, $context, $info) {
            // Do any logic here to sanitize the input, check user capabilities, etc

            $Output = $input;

            return [
                'out' => $Output,
            ];
        }
    ]);
};




/**
 * Check whether Woocommerce Gold Price and WPGraphQL are active, and whether the minimum version requirement has been
 * met
 *
 * @return bool
 * @since 0.3
 */
function can_load_plugin()
{
    // Is Woocommerce Gold Price active?
    if (!class_exists('woocommerce_gold_price')) {
        return false;
    }

    // Is WPGraphQL active?
    if (!class_exists('WPGraphQL')) {
        return false;
    }

    // Do we have a WPGraphQL version to check against?
    if (empty(defined('WPGRAPHQL_VERSION'))) {
        return false;
    }

    // Have we met the minimum version requirement?
    if (true === version_compare(WPGRAPHQL_VERSION, WPGRAPHQL_REQUIRED_MIN_VERSION, 'lt')) {
        return false;
    }

    return true;
}
