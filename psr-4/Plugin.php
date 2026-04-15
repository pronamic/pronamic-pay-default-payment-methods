<?php
/**
 * Plugin
 *
 * @package Pronamic\PronamicPayDefaultPaymentMethods
 */

namespace Pronamic\PronamicPayDefaultPaymentMethods;

use Pronamic\WordPress\Pay\Core\PaymentMethod;
use Pronamic\WordPress\Pay\Core\PaymentMethodsCollection;

/**
 * Plugin class
 */
class Plugin {
	/**
	 * Instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Get instance.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		\add_action( 'pronamic_pay_register_payment_methods', $this->register_payment_methods( ... ), 10, 1 );
	}

	/**
	 * Register payment methods.
	 *
	 * @param PaymentMethodsCollection $payment_methods Payment methods collection.
	 * @return void
	 */
	private function register_payment_methods( PaymentMethodsCollection $payment_methods ): void {
		$files = \glob( __DIR__ . '/../payment-methods/*.php' );

		if ( false === $files ) {
			return;
		}

		foreach ( $files as $file ) {
			$payment_method = include $file;

			if ( ! $payment_method instanceof PaymentMethod ) {
				throw new \RuntimeException(
					\sprintf(
						'Expected file %s to return a PaymentMethod instance, got %s.',
						$file,
						\get_debug_type( $payment_method )
					)
				);
			}

			$payment_methods->add( $payment_method );
		}
	}
}
