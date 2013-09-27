 /*! ExMachina Admin Setup JS v1.0.0 | (c) 2013, Machina Themes | http://machinathemes.com/ */

/**
 * ExMachina WordPress Theme Engine Framework
 * ExMachina Admin Setup JS
 *
 * All JavaScript logic for the theme options admin interface.
 *
 * @package     ExMachina
 * @subpackage  Admin Assets
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Machina Themes
 */

(function ($) {

  ExMachinaAdminInit = {

    /**
     * [init_minicolors description]
     * @return {[type]} [description]
     */
    init_minicolors: function () {
      $('.colourpicker').minicolors({
        control: 'hue',
      });
    }, // end init_minicolors()

    /**
     * [init_pretty_selects description]
     * @return {[type]} [description]
     */
    init_pretty_selects: function () {
      $(".pretty-select").select2({
        minimumResultsForSearch: 10
      });
    }, // end init_pretty_selects()

    /**
     * [setup_settings_errors description]
     * @return {[type]} [description]
     */
    setup_settings_errors: function () {
      $( ".settings-error" ).attr( "data-uk-alert", '' ).prepend( "<button class='uk-alert-close uk-close'>" );
    }, // end setup_settings_errors()

    /**
     * [setup_default_wp_colorpicker description]
     * @return {[type]} [description]
     */
    setup_default_wp_colorpicker: function () {
      $('.wp-color-picker-field').wpColorPicker();
    }, // end setup_default_wp_colorpicker()

    /**
     * [setup_custom_typography description]
     * @return {[type]} [description]
     */
    setup_custom_typography: function () {
      $( 'select.typography-unit' ).change( function(){

        var val = $( this ).val();
        var parent = $( this ).parent();
        var name = parent.find( '.typography-size-px' ).attr( 'name' );

        if( name == '' ) { var name = parent.find( '.typography-size-em' ).attr( 'name' ); }

        if( val == 'px' ) {
        var name = parent.find( '.typography-size-em' ).attr( 'name' );
        parent.find( '.typography-size-em' ).hide().removeAttr( 'name' );
        parent.find( '.typography-size-px' ).show().attr( 'name', name );
      }
      else if( val == 'em' ) {
        var name = parent.find( '.typography-size-px' ).attr( 'name' );
        parent.find( '.typography-size-px' ).hide().removeAttr( 'name' );
        parent.find( '.typography-size-em' ).show().attr( 'name', name );
      }

      });
    }, // end setup_custom_typography()

    /**
     * [setup_colorpickers description]
     * @return {[type]} [description]
     */
    setup_colorpickers: function () {
      if ( jQuery().ColorPicker && $( '.section-typography, .section-border, .section-color' ).length ) {

        $( '.section-typography, .section-border, .section-color' ).each(function () {

          var option_id = $( this ).find( '.pick-color' ).attr( 'id' );
          var color = $( this ).find( '.pick-color' ).val();
          var picker_id = option_id += '_picker';

          if ( $( this ).hasClass( 'section-typography' ) || $( this ).hasClass( 'section-border' ) ) {
            option_id += '_color';
          } // end if ( $(this).hasClass('section-typography') || $(this).hasClass('section-border'))

          $( '#' + picker_id ).children( 'div' ).css( 'backgroundColor', color );
          $( '#' + picker_id ).ColorPicker({
            color: color,
            onChange: function ( hsb, hex, rgb ) {
              $( '#' + picker_id ).children( 'div' ).css( 'backgroundColor', '#' + hex );
              $( '#' + picker_id ).next( 'input' ).attr( 'value', '#' + hex );
            } // end onChange: function(hsb, hex, rgb)
          }); // end $('#' + picker_id).ColorPicker()
        });

      } // end if ( jQuery().ColorPicker && $('.section-typography, .section-border, .section-color').length)
    }, // end setup_colorpickers()

  }; // end ExMachinaAdminInit object.

  /*-------------------------------------------------------------------------*/
  /* Execute the above methods in the ExMachinaAdminInit object.
  /*-------------------------------------------------------------------------*/

  $(document).ready(function () {

    // Bind the minicolors JavaScript to the '.colourpicker' class.
    ExMachinaAdminInit.init_minicolors();

    // Bind the 'select2' JavaScript to the '.pretty-select' class.
    ExMachinaAdminInit.init_pretty_selects();

    // Setup the settings errors close button.
    ExMachinaAdminInit.setup_settings_errors();

    ExMachinaAdminInit.setup_default_wp_colorpicker();

    // Setup the custom typography font unit selectors.
    ExMachinaAdminInit.setup_custom_typography();

    // Setup the colorpicker update field.
    ExMachinaAdminInit.setup_colorpickers();

  });

})(jQuery);