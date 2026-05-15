<?php

namespace BetterFrameworkPackage\Component\Control\TermSelect;

use Walker_Category;
use BetterFrameworkPackage\Component\Control;

if ( ! class_exists( Walker_Category::class ) ) {

	require ABSPATH . WPINC . '/class-walker-category.php';
}

class TermSelectWalker extends Walker_Category {

	public function start_el( &$output, $category, $depth = 0, $args = [], $id = 0 ) {

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

		$el = '<span class="label" data-status="">' . $cat_name;

		if ( ! empty( $args['show_count'] ) ) {
			$el .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		$output     .= "\t<li";
		$css_classes = [
			'cat-item',
			'cat-item-' . $category->term_id,
		];

		if ( ! empty( $args['current_category'] ) ) {
			// 'current_category' can be an array, so we use `get_terms()`.
			$_current_terms = get_terms(
				$category->taxonomy,
				[
					'include'    => $args['current_category'],
					'hide_empty' => false,
				]
			);

			foreach ( $_current_terms as $_current_term ) {
				if ( $category->term_id == $_current_term->term_id ) {
					$css_classes[] = 'current-cat';
				} elseif ( $category->term_id == $_current_term->parent ) {
					$css_classes[] = 'current-cat-parent';
				}
				while ( $_current_term->parent ) {
					if ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-ancestor';
						break;
					}
					$_current_term = get_term( $_current_term->parent, $category->taxonomy );
				}
			}
		}

		/**
		 * Filter the list of CSS classes to include with each category in the list.
		 *
		 * @see   wp_list_categories()
		 *
		 * @param array  $css_classes An array of CSS classes to be applied to each list item.
		 * @param object $category    Category data object.
		 * @param int    $depth       Depth of page, used for padding.
		 * @param array  $args        An array of wp_list_categories() arguments.
		 */
		$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

		$output .= ' class="' . $css_classes . '"';
		$output .= '>';
		$output .= $this->checkbox_field( $category, $args['input_name'] );
		$output .= "$el\n";

		$output .= '</span>';
	}


	protected function checkbox_field( $term, $input_name ) {

		ob_start();
		?>
		<!-- Start checkbox input -->
		<div class="bf-checkbox-multi-state"
			 data-current-state="none"
		>
			<input type="hidden"
				   name="<?php echo esc_attr( $input_name ); ?>[<?php echo intval( $term->term_id ); ?>]"
				   class="bf-checkbox-status"
				   value="none">

			<span data-state="none" class="bf-checkbox-icon"></span>
			<span data-state="active" class="bf-checkbox-icon">
			 <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-check' ); ?>
			</span>
			<span data-state="deactivate" class="bf-bf-checkbox-icon">
				   <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-times' ); ?>
			</span>
		</div>
		<!-- END checkbox input -->
		<?php

		return ob_get_clean();
	}
}
