<?php

namespace Basecamp;


/**
 * Setup ACF services
 */
class Acf
{
    /**
     * Init
     */
    public static function init()
    {
        add_action('acf/init', function() {
            $googleApiKey = get_field('googleMapsAPIKey', 'option');
            acf_update_setting('google_api_key', $googleApiKey);
        });
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page();
        }
        new AcfNavMenuField();
    }
}

/*
* Add ACF nav menu field
* https://github.com/jgraup/advanced-custom-fields-nav-menu-field
*/
class AcfNavMenuField extends \acf_field
{
	// vars
	public $settings,
		$defaults;

    /**
     * Set name / label needed for actions / filters
     */
	function __construct()
	{
		$this->name = 'nav_menu';
		$this->label = __('Nav Menu', 'acf');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'allow_null' => -1,
		);
		parent::__construct();
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.1.2'
		);
	}

    /**
     * Render settings
     *
     * @param array $field
     */
	function render_field_settings( $field )
	{
		acf_render_field_setting( $field, array(
			'label'			=> __('Allow Null?','acf'),
			'type'	=>	'radio',
			'name'		=> 'allow_null',
			'choices'	=>	array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			),
			'layout'	=>	'horizontal',
		));
	}

    /**
     * Render the field.
     *
     * @param array $field
     */
	function render_field( $field )
	{

		echo sprintf( '<select id="%d" class="%s" name="%s">', $field['id'], $field['class'], $field['name']  );
		if( $field['allow_null'] )
		{
			echo '<option value=""> - Select - </option>';
		}
		$nav_menus = $this->get_nav_menus();
		foreach( $nav_menus as $nav_menu_id => $nav_menu_name ) {
			$selected = selected( $field['value'], $nav_menu_id );
			echo sprintf( '<option value="%1$d" %3$s>%2$s</option>', $nav_menu_id, $nav_menu_name, $selected );
		}
		echo '</select>';
    }

    /**
     * Get WP menus.
     */
	function get_nav_menus() {
		$navs = get_terms('nav_menu', array( 'hide_empty' => false ) );

		$nav_menus = array();
		foreach( $navs as $nav ) {
			$nav_menus[ $nav->term_id ] = $nav->name;
		}
		return $nav_menus;
    }
}
