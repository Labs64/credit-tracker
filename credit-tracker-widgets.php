<?php
/**
 * Plugin widgets.
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2017 Labs64
 */

// Register and load the widgets.
function credit_tracker_load_widgets() {
	// Register the table widget.
	register_widget( 'credit_tracker_table_widget' );
}
add_action( 'widgets_init', 'credit_tracker_load_widgets' );

class credit_tracker_table_widget extends WP_Widget {
	protected $style_types = array( 'default', 'mercury', 'mars' );

	function __construct() {
		parent::__construct(
			'credit_tracker_table_widget',
			__( 'Credit Tracker Table Widget', CREDITTRACKER_SLUG ),
			array(
				'description' => __( 'Render the [credit_tracker_table] shortcode within this widget.', CREDITTRACKER_SLUG ),
			)
		);
	}

	// Render the widget.
	public function widget( $args, $instance ) {
		$interpolated_shortcode = sprintf(
			'[credit_tracker_table%1$s%2$s%3$s%4$s]',
			( isset( $instance['ids'] ) && ! empty( $instance['ids'] ) ) ? " id=\"{$instance['ids']}\"" : '',
			( isset( $instance['size'] ) && ! empty( $instance['size'] ) ) ? " size=\"{$instance['size']}\"" : '',
			( isset( $instance['include_columns'] ) && ! empty( $instance['include_columns'] ) ) ? " include_columns=\"{$instance['include_columns']}\"" : '',
			( isset( $instance['style'] ) && ! empty( $instance['style'] ) ) ? " style=\"{$instance['style']}\"" : ''
		);

		echo do_shortcode( $interpolated_shortcode );
	}

	// Render the wp-admin input for the widget.
	public function form( $instance ) {
		// Default values, if the instances do not yet exist.
		$instance['ids'] = isset( $instance['ids'] ) ? $instance['ids'] : '';
		$instance['size'] = isset( $instance['size'] ) ? $instance['size'] : '';
		$instance['include_columns'] = isset( $instance['include_columns'] ) ? $instance['include_columns'] : '';
		$instance['style'] = isset( $instance['style'] ) ? $instance['style'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'ids' ); ?>">
				<?php _e( 'IDs (optional, comma separated):', CREDITTRACKER_SLUG ); ?>
			</label>
			<input class="widefat"
				   id="<?php echo $this->get_field_id( 'ids' ); ?>"
				   name="<?php echo $this->get_field_name( 'ids' ); ?>"
				   type="text"
				   value="<?php echo esc_attr( $instance[ 'ids' ] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>">
				<?php _e( 'Size (optional):', CREDITTRACKER_SLUG ); ?>
			</label>
			<input class="widefat"
				   id="<?php echo $this->get_field_id( 'size' ); ?>"
				   name="<?php echo $this->get_field_name( 'size' ); ?>"
				   type="text"
				   value="<?php echo esc_attr( $instance[ 'size' ] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include_columns' ); ?>">
				<?php _e( 'Include Columns (optional, comma separated):', CREDITTRACKER_SLUG ); ?>
			</label>
			<input class="widefat"
				   id="<?php echo $this->get_field_id( 'include_columns' ); ?>"
				   name="<?php echo $this->get_field_name( 'include_columns' ); ?>"
				   type="text"
				   value="<?php echo esc_attr( $instance[ 'include_columns' ] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>">
				<?php _e( 'Style (optional):', CREDITTRACKER_SLUG ); ?>
			</label>
			<select class="widefat"
					id="<?php echo $this->get_field_id( 'style' ); ?>"
					name="<?php echo $this->get_field_name( 'style' ); ?>">
				<?php
				foreach ( $this->style_types as $style ) {
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $style ),
						selected( $instance[ 'style' ], $style, false ),
						esc_html( ucfirst( $style ) )
					);
				}
				?>
			</select>
		</p>
		<p class="description">Refer to instructions on fields, <a href="<?php echo esc_url( get_admin_url( null, 'options-general.php?page=credit-tracker' ) ); ?>" target="_blank">here</a>.</p>
		<?php
	}

	// Update the values for the widget, on save.
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['ids']   = sanitize_text_field( $new_instance['ids'] );
		$instance['size']  = sanitize_text_field( $new_instance['size'] );
		$instance['include_columns'] = sanitize_text_field( $new_instance['include_columns'] );

		if ( ! in_array( $new_instance['style'], $this->style_types ) ) {
			$new_instance['style'] = 'default';
		}
		$instance['style'] = sanitize_text_field( $new_instance['style'] );

		return $instance;
	}
}
