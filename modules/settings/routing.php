<?php

	/*
	* Copyright (C) 2016-2019 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

    //Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

    if(admin()){

		echo "
			'settings': function(name) {
		    $( '#navigation_top' ).hide();
				$( '#content_holder' ).hide();
				$( '#loader' ).show();
				$( '#titletext' ).text('Settings');
				document.title = 'Settings';
				$( '#content_holder' ).load( 'modules/settings/settings.php', function() { init_page(); });

				$('#navigation_top').show();
				$('#navigation_top').load('modules/".basename(__DIR__)."/menu.php', function() {
					$('.tab_1').addClass('tabmenuover');
				});

				ga('set', 'page', '/#settings/');
				ga('send', 'pageview');
	    },
	    'settings/integrations': function(name) {
		    $( '#navigation_top' ).hide();
				$( '#content_holder' ).hide();
				$( '#loader' ).show();
				$( '#titletext' ).text('Settings');
				document.title = 'Settings';
				$( '#content_holder' ).load( 'modules/settings/integrations.php', function() { init_page(); });

				$('#navigation_top').show();
				$('#navigation_top').load('modules/".basename(__DIR__)."/menu.php', function() {
					$('.tab_2').addClass('tabmenuover');
				});

				ga('set', 'page', '/#settings/integrations/');
				ga('send', 'pageview');
	    },
	    'settings/authentication': function(name) {
		    $( '#navigation_top' ).hide();
				$( '#content_holder' ).hide();
				$( '#loader' ).show();
				$( '#titletext' ).text('Settings');
				document.title = 'Settings';
				$( '#modal_holder' ).load( 'modules/settings/modals.php' );
				$( '#content_holder' ).load( 'modules/settings/authentication.php', function() { init_page(); });

				$('#navigation_top').show();
				$('#navigation_top').load('modules/".basename(__DIR__)."/menu.php', function() {
					$('.tab_3').addClass('tabmenuover');
				});

				ga('set', 'page', '/#settings/authentication/');
				ga('send', 'pageview');
	    },
	    'settings/usage': function(name) {
				$( '#navigation_top' ).hide();
				$( '#content_holder' ).hide();
				$( '#loader' ).show();
				$( '#titletext' ).text('Settings');
				document.title = 'Settings';
				$( '#content_holder' ).load( 'modules/settings/usage.php', function() { init_page(); });

				$('#navigation_top').show();
				$('#navigation_top').load('modules/".basename(__DIR__)."/menu.php', function() {
					$('.tab_4').addClass('tabmenuover');
				});

				ga('set', 'page', '/#settings/usage/');
				ga('send', 'pageview');
	    },'settings/diagnostics': function(name) {
				$( '#navigation_top' ).hide();
				$( '#content_holder' ).hide();
				$( '#loader' ).show();
				$( '#titletext' ).text('Settings');
				document.title = 'Settings';
				$( '#content_holder' ).load( 'modules/settings/diagnostics.php', function() { init_page(); });

				$('#navigation_top').show();
				$('#navigation_top').load('modules/".basename(__DIR__)."/menu.php', function() {
					$('.tab_5').addClass('tabmenuover');
				});

				ga('set', 'page', '/#settings/diagnostics/');
				ga('send', 'pageview');
	    },";
	}
?>