<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

use \BetterFrameworkPackage\Component\Control\IconSelect\{
	Fontawesome,
	BsIcons
};

use BetterFrameworkPackage\Component\Control;

// Get fontawesome instance
$fontawesome = new \BetterFrameworkPackage\Component\Control\IconSelect\Fontawesome();
// BS Icons
$bs_icons = new \BetterFrameworkPackage\Component\Control\IconSelect\BsIcons();

// Default selected
$current = [
	'key'   => '',
	'title' => '',
];

if ( ! $custom_icons = get_option( 'bf_custom_icons_list', [] ) ) {

	$custom_icons = [];
}

?>
<div id="better-icon-modal" class="better-modal icon-modal" data-remodal-id="better-icon-modal" role="dialog">
    <div class="modal-inner">

        <div class="modal-header">
            <span><?php esc_html_e( 'Chose an Icon', 'better-studio' ); ?></span>
            <div class="better-icons-search bf-clearfix">
                <input type="text" class="better-icons-search-input"
                       placeholder="<?php esc_html_e( 'Search...', 'better-studio' ); ?>"/>
	            <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-search', 'clean' ); ?>
            </div>
        </div><!-- modal header -->

        <div class="modal-body bf-clearfix">

            <div class="icons-container bf-clearfix">

                <div class="icons-inner bf-clearfix">
                    <h2 class="font-type-header">
                        <span class="title"><?php esc_html_e( 'Custom Icons', 'better-studio' ); ?></span>
                        <span data-button-text="<?php esc_html_e( 'Select Icon', 'better-studio' ); ?>"
                              data-media-title="<?php esc_html_e( 'Select Icon', 'better-studio' ); ?>"
                              class="upload-custom-icon button button-primary button-small">
                            <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload', 'bf-icon' ); ?>
                            <?php esc_html_e( 'Upload Custom Icon', 'better-studio' ); ?>
                        </span>
                    </h2>
                    <ul class="icons-list custom-icons-list bf-clearfix">
						<?php foreach ( (array) $custom_icons as $icon ) { ?>

                            <li data-id="<?php echo esc_attr( $icon['id'] ); ?>"
                                class="icon-select-option custom-icon"
                                data-custom-icon="<?php echo esc_attr( $icon['icon'] ); ?>"
                                data-width="<?php echo esc_attr( $icon['width'] ?? '' ); ?>"
                                data-height="<?php echo esc_attr( $icon['height'] ?? '' ); ?>"
                                data-type="custom-icon">
								<?php echo bf_get_icon_tag( $icon ); ?>
                                <span class="delete-icon"></span>
                            </li>
						<?php } ?>
                    </ul><!-- icons list -->

                    <p class="no-custom-icon <?php echo $custom_icons ? 'hidden' : ''; ?>"><?php esc_html_e( 'No custom icon created!.', 'better-studio' ); ?></p>

                    <!-- FontAwesome Icons -->
                    <h2 class="font-type-header"><span
                                class="title"><?php esc_html_e( 'Fontawesome Icons', 'better-studio' ); ?></span></h2>
                    <ul class="icons-list font-icons bf-clearfix">
                        <li data-value="" data-label="<?php esc_html_e( 'Chose an Icon', 'better-studio' ); ?>"
                            class="icon-select-option default-option">
                            <p><?php esc_html_e( 'No Icon', 'better-studio' ); ?></p>
                        </li>
						<?php
                        foreach ( $fontawesome->icons as $key => $icon ) {

							?>

                            <li <?php $fontawesome->html_attributes( $key, $key === $current['key'] ); ?>
                                    class="icon-select-option <?php echo( $key === $current['key'] ? 'selected' : '' ); ?>"
                            >
								<?php echo $fontawesome->get_icon_tag( $key ); // escaped before in function ?>
                                <span class="label"><?php echo esc_html( $icon['label'] ); ?></span>
                            </li>

<?php } ?>
                    </ul><!-- icons list -->

                    <!-- /FontAwesome Icons -->

                    <!-- BS Icons -->
                    <h2 class="font-type-header">
                        <span class="title">
                            <?php esc_html_e( 'BetterStudio Icons', 'better-studio' ); ?>
                        </span>
                    </h2>
                    <ul class="icons-list font-icons bf-clearfix">
						<?php
                        foreach ( $bs_icons->icons as $key => $icon ) {
							$categories = '';

							if ( isset( $icon['category'] ) ) {
								foreach ( $icon['category'] as $category ) {
									$categories .= ' cat-' . $category;
								}
							}
							?>
                            <li <?php $bs_icons->html_attributes( $key, $key === $current['key'] ); ?>>
								<?php echo $bs_icons->get_icon_tag( $key ); // escaped before in function ?> <span
                                        class="label"><?php echo esc_html( $icon['label'] ); ?></span>
                            </li>
<?php } ?>
                    </ul><!-- icons list -->
                    <!-- /BS Icons -->
                </div><!-- /icons inner -->
            </div><!-- /icons container -->

            <div class="cats-container bf-clearfix">

                <ul class="better-icons-category-list bf-clearfix">
                    <li class="icon-category selected" id="cat-all">
                        <span data-cat="#cat-all"><?php esc_html_e( 'All ', 'better-studio' ); ?></span> <span
                                class="text-muted">(<?php echo \count( $fontawesome->icons ) + \count( $bs_icons->icons ); ?>
							)</span>
                    </li>
					<?php foreach ( (array) $fontawesome->categories as $key => $category ) { ?>
                        <li class="icon-category" id="cat-<?php echo esc_attr( $category['id'] ); ?>">
							<span
                                    data-cat="#cat-<?php echo esc_attr( $category['id'] ); ?>"><?php echo esc_html( $category['label'] ); ?></span>
                            <span class="text-muted">(<?php echo esc_html( $category['counts'] ); ?>)</span>
                        </li>
					<?php } ?>

					<?php foreach ( (array) $bs_icons->categories as $key => $category ) { ?>
                        <li class="icon-category" id="cat-<?php echo esc_attr( $category['id'] ); ?>">
                            <span data-cat="#cat-<?php echo esc_attr( $category['id'] ); ?>"><?php echo esc_html( $category['label'] ); ?></span>
                            <span class="text-muted">(<?php echo esc_html( $category['counts'] ); ?>)</span>
                        </li>
					<?php } ?>
                </ul><!-- categories list -->
            </div><!-- /cats container -->

            <div class="upload-custom-icon-container">
                <div class="upload-custom-icon-inner">
                    <div class="custom-icon-fields">
                        <div class="section-header">
                            <span><?php esc_html_e( 'Insert Custom Icon', 'better-studio' ); ?></span>
                        </div>
                        <div class="section-body">
                            <span class="icon-helper"></span>
                            <img src="" class="icon-preview"/>
                        </div>
                        <div class="icon-fields">
							<?php esc_html_e( 'Width:', 'better-studio' ); ?> <input type="text" name="icon-width"
                                                                                     placeholder="<?php esc_html_e( 'Auto', 'better-studio' ); ?>">
							<?php esc_html_e( 'Height:', 'better-studio' ); ?> <input type="text" name="icon-height"
                                                                                      placeholder="<?php esc_html_e( 'Auto', 'better-studio' ); ?>">
                        </div>

                        <div class="section-footer">
                            <a href="#"
                               class="button button-primary button-large bf-main-button"><?php esc_html_e( 'Insert Icon', 'better-studio' ); ?></a>
                        </div>
                    </div>
                    <div class="icon-uploader-loading">
                        <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-refresh', 'fa-spin' ); ?>
                    </div>

                    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-close', 'close-custom-icon' ); ?>
                </div>
            </div>
        </div><!-- /modal body -->
    </div><!-- /modal inner -->
</div><!-- /modal -->
