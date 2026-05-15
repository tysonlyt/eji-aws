<?php

/*
 * Fix: master-slider update issue.
 *
 * @hooked after_setup_theme
 *
 * @param mixed $transient
 *
 * @since  7.8.1
 * @return mixed
 */

class_exists( 'Axiom_Plugin_Updater' ) && bf_remove_class_filter( 'site_transient_update_plugins', 'Axiom_Plugin_Updater', 'define_package_for_plugin_update_transient' );
