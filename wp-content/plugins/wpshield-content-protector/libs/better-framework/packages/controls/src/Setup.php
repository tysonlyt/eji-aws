<?php

namespace BetterFrameworkPackage\Component\Control;

// use Integration library
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use utility libraries
use \BetterFrameworkPackage\Utils\{
	Validator
};

// use core libraries
use \BetterFrameworkPackage\Core\{
	Module,
	Rest as RestLib
};

class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * Store list of the registered render callbacks.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected static $wrappers = [];

	public static function setup(): bool {

		self::introduce();
				\BetterFrameworkPackage\Core\Rest\RestSetup::register( \BetterFrameworkPackage\Component\Control\Core\Rest\RestRequestProps::class );
		\BetterFrameworkPackage\Core\Rest\RestSetup::register( \BetterFrameworkPackage\Component\Control\Core\Rest\RestRequestData::class );
		\BetterFrameworkPackage\Core\Rest\RestSetup::setup();
				\BetterFrameworkPackage\Utils\Validator\Factory::register( 'bs-control', \BetterFrameworkPackage\Component\Control\Core\Validation\ControlValidation::class );

		\BetterFrameworkPackage\Component\Control\Features\ProFeature::setup();

		return true;
	}

	/**
	 * Register controls list.
	 *
	 * @since 1.0.0
	 */
	public static function introduce(): void {

		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'hr', \BetterFrameworkPackage\Component\Control\Hr\HrControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'button', \BetterFrameworkPackage\Component\Control\Button\ButtonControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'text', \BetterFrameworkPackage\Component\Control\Text\TextControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'info', \BetterFrameworkPackage\Component\Control\Info\InfoControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'heading', \BetterFrameworkPackage\Component\Control\Heading\HeadingControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'code', \BetterFrameworkPackage\Component\Control\Code\CodeControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'radio', \BetterFrameworkPackage\Component\Control\Radio\RadioControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'color', \BetterFrameworkPackage\Component\Control\Color\ColorControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'media', \BetterFrameworkPackage\Component\Control\Media\MediaControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'wp_editor', \BetterFrameworkPackage\Component\Control\WpEditorControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'select', \BetterFrameworkPackage\Component\Control\Select\SelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'editor', \BetterFrameworkPackage\Component\Control\Editor\EditorControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'custom', \BetterFrameworkPackage\Component\Control\Custom\CustomControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'export', \BetterFrameworkPackage\Component\Control\Export\ExportControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'import', \BetterFrameworkPackage\Component\Control\Import\ImportControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'slider', \BetterFrameworkPackage\Component\Control\Slider\SliderControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'repeater', \BetterFrameworkPackage\Component\Control\Repeater\RepeaterControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'sorter', \BetterFrameworkPackage\Component\Control\Sorter\SorterControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'textarea', \BetterFrameworkPackage\Component\Control\Textarea\TextareaControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'checkbox', \BetterFrameworkPackage\Component\Control\Checkbox\CheckboxControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'switch', \BetterFrameworkPackage\Component\Control\SwitchControl\SwitchControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'typography', \BetterFrameworkPackage\Component\Control\Typography\TypographyControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'ajax_select', \BetterFrameworkPackage\Component\Control\AjaxSelect\AjaxSelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'icon_select', \BetterFrameworkPackage\Component\Control\IconSelect\IconSelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'term_select', \BetterFrameworkPackage\Component\Control\TermSelect\TermSelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'ajax_action', \BetterFrameworkPackage\Component\Control\AjaxAction\AjaxActionControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'media_image', \BetterFrameworkPackage\Component\Control\MediaImage\MediaImageControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'image_radio', \BetterFrameworkPackage\Component\Control\ImageRadio\ImageRadioControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'select_popup', \BetterFrameworkPackage\Component\Control\SelectPopup\SelectPopupControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'image_upload', \BetterFrameworkPackage\Component\Control\ImageUpload\ImageUploadControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'image_select', \BetterFrameworkPackage\Component\Control\ImageSelect\ImageSelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'image_preview', \BetterFrameworkPackage\Component\Control\ImagePreview\ImagePreviewControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'advance_select', \BetterFrameworkPackage\Component\Control\AdvanceSelect\AdvanceSelectControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'modal_connector', \BetterFrameworkPackage\Component\Control\ModalConnector\ModalConnectorControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'multiple_controls', \BetterFrameworkPackage\Component\Control\MultipleControls\MultipleControls::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'sorter_checkbox', \BetterFrameworkPackage\Component\Control\SorterCheckbox\SorterCheckboxControl::class );
		\BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::register( 'background_image', \BetterFrameworkPackage\Component\Control\BackgroundImage\BackgroundImageControl::class );
	}


	/**
	 * Register a render callback.
	 *
	 * @param string   $wrapper_id       a unique id for the wrapper .
	 * @param callable $wrapper_callback the render callback.
	 *
	 * @since 1.0.0
	 * @return bool true on success.
	 */
	public static function register_wrapper( string $wrapper_id, callable $wrapper_callback ): bool {

		if ( isset( static::$wrappers[ $wrapper_id ] ) ) {

			return false;
		}

		if ( ! \is_callable( $wrapper_callback ) ) {
			return false;
		}

		static::$wrappers[ $wrapper_id ] = $wrapper_callback;

		return true;
	}

	/**
	 * @param string $wrapper_id
	 *
	 * @return callable|null
	 */
	public static function wrapper( string $wrapper_id ): ?callable {

		return static::$wrappers[ $wrapper_id ] ?? null;
	}
}
