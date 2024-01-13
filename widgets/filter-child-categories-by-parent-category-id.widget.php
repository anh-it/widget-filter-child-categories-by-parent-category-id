<?php

class WFCCBPCI_Widget extends WP_Widget {
	/**
	 * Constructor for the FilterChildCategoriesWidget class.
	 *
	 * @param string $id_base The ID base for the widget.
	 * @param string $name The name of the widget.
	 * @param array $widget_options The widget options.
	 * @param array $control_options The control options.
	 */
	public function __construct(
		$id_base = 'wpl_WFCCBPCI',
		$name = 'Widget Filter Child Categories By Parent Category ID',
		$widget_options = array(
			'description' => 'Widget Filter Child Categories By Parent Category ID',
			'customize_selective_refresh' => true
		),
		$control_options = array()
	) {
		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}

	/**
	 * Generate the form HTML for the widget.
	 *
	 * @param array $instance The current widget instance's settings.
	 *
	 * @return void The HTML string for the form.
	 */
	public function form( $instance ): void {
//		parent::form( $instance );
		// Set default values for the instance
		$default  = array(
			'cat_parent' => (string) '0,Uncategorized',
			'slug'   => (string) 'uncategorized',
		);
		$instance = wp_parse_args( (array) $instance, (array) $default );

		// Get all categories

		$taxonomy      = 'category';
		$uncategorized = get_term_by( 'name', 'Uncategorized', $taxonomy );
		$exclude       = $uncategorized->term_id;
		$terms         = get_terms( $taxonomy, array(
			'exclude'    => $exclude,
			'hide_empty' => false,
			'order'      => 'ASC',
			'parent'     => 0
		) );

		$pages = get_pages(array(
			'exclude'    => $exclude,
			'hide_empty' => false,
			'order'      => 'ASC',
			'parent'     => 0
		) );

		$html = '<div class="wfccbpci__container">'. PHP_EOL;
		$html .= '<p>'. PHP_EOL;
		$html .='<label for="' . esc_attr( $this->get_field_id( 'cat_parent' ) ) . '" class="wfccbpci__session-title">' . __( 'Category parent' ) . '</label>' . PHP_EOL;
		$html .='<select class="widefat" id="' . esc_attr( $this->get_field_id( 'cat_parent' ) ) . '" name="' . esc_attr__( $this->get_field_name( 'cat_parent' ) ) . '">' . PHP_EOL;
		$html .='<option value="0,Uncategorized" selected disabled>Select category parent</option>' . PHP_EOL;
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$html .= '<option value="' . esc_attr( $term->term_id ) . ',' . esc_attr( $term->name ) . '"' . selected( $instance['cat_parent'], esc_attr( $term->term_id ) . ',' . esc_attr( $term->name ) , false ) . '>' . esc_html( $term->name ) . '</option>';
			}
		}
		$html .= '</select>' . PHP_EOL;
		$html .= '</p>';

		$html .= '<i class="fas fa-link fa-lg"></i>'. PHP_EOL;

		$html .= '<p>'. PHP_EOL;
		$html .='<label for="' . esc_attr( $this->get_field_id( 'slug' ) ) . '" class="wfccbpci__session-title">' . __( 'Page' ) . '</label>' . PHP_EOL;
		$html .='<select class="widefat" id="' . esc_attr( $this->get_field_id( 'slug' ) ) . '" name="' . esc_attr__( $this->get_field_name( 'slug' ) ) . '">' . PHP_EOL;
		$html .='<option value="uncategorized" selected disabled>Select page</option>' . PHP_EOL;
		if ( ! empty( $pages ) && ! is_wp_error( $pages ) ) {
			foreach ( $pages as $page ) {
				$html .= '<option value="' . esc_attr( $page->post_name) . '"' . selected( $instance['slug'], esc_attr( $page->post_name ) , false ) . '>' . esc_html( $page->post_title ) . '</option>';
			}
		}
		$html .= '</select>' . PHP_EOL;
		$html .= '</p>'. PHP_EOL;
		$html .='</div>'. PHP_EOL;
		echo( $html );
	}

	/**
	 * Update the widget instance with new values.
	 *
	 * @param array $new_instance The new values of the widget instance.
	 * @param array $old_instance The old values of the widget instance.
	 *
	 * @return array The updated widget instance.
	 */
	public function update( $new_instance, $old_instance ): array {
		// Call the parent class update method
		parent::update( $new_instance, $old_instance );

		// Create a copy of the old instance
		$instance = $old_instance;
		// Update the 'id_parent' value with the new value, after stripping any HTML tags
		$instance['cat_parent'] = strip_tags( $new_instance['cat_parent'] );
		$instance['slug'] = strip_tags( $new_instance['slug'] );

		// Return the updated widget instance
		return $instance;
	}

	/**
	 * Render the widget.
	 *
	 * @param array $args The widget arguments.
	 * @param array $instance The widget instance.
	 */
	public function widget( $args, $instance ): void {
		// Extract the arguments.
		extract( $args );

		$instance_base      = explode( ',', $instance['cat_parent'] );
		$parent_category_id = (integer) $instance_base[0];
		$name_category      = (string) $instance_base[1];
		$slug = explode( ',', $instance['slug'] );

		// Get slug page current
		$current_url = home_url(add_query_arg(array()));
		$path = parse_url($current_url, PHP_URL_PATH);
		$path_cleaned = str_replace(array('/', '\\'), '', $path);

		if($slug[0] !== $path_cleaned){
			return;
		}

		// Get the widget title.
		$title = apply_filters( 'widget_title', $name_category );

		// Set the taxonomy.
		$taxonomy = 'category';

		// Get the child categories.
		$child_categories = get_terms( array(
			'taxonomy'   => $taxonomy,
			'parent'     => $parent_category_id,
			'hide_empty' => false,
			'order'      => 'ASC',
		) );

		// Render the widget.
		echo $before_widget;

		// Render the widget title.
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Render the child categories.
		$html = '<ul>' . PHP_EOL;
		if ( $child_categories ) {
			foreach ( $child_categories as $term ) {
				$html .= '<li class="cat-item cat-item-' . $term->term_id . '" id="term-' . $term->term_id . '" aria-expanded="true"><a href="' . get_term_link( $term, $taxonomy ) . '">' . $term->name . '</a></li>' . PHP_EOL;
			}
		} else {
			$html .= '<li class="cat-item cat-item-0"><a href="' . get_term_link( 'Uncategorized', $taxonomy ) . '">Uncategorized</a></li>' . PHP_EOL;
		}
		$html .= '</ul>';
		echo $html;

		// Render the widget closing tag.
		echo $after_widget;
	}

}

/**
 * Registers the WFCCBPCI_Widget widget.
 *
 * @return void
 */
function registerMyWidget(): void {
	register_widget( 'WFCCBPCI_Widget' );
}

add_action( 'widgets_init', 'registerMyWidget' );