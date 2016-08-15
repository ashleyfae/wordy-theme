<?php
/**
 * Customizer Heading Control
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class Wordy_Heading_Control extends WP_Customize_Control {

	/**
	 * Control Type
	 *
	 * @var string
	 */
	public $type = 'wordy_heading';

	/**
	 * Render the control's content.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function render_content() {
		if ( ! empty( $this->label ) ) : ?>
			<h2><?php echo esc_html( $this->label ); ?></h2>
		<?php endif;

		if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif;
	}

}