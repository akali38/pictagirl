<?php
add_action( 'wp_enqueue_scripts', 'ifba_enqueue_styles', 10);
add_action( 'admin_enqueue_scripts', 'ifba_admin_enqueue_styles', 10);

function ifba_enqueue_styles() {
	
		wp_enqueue_script('jquery');
		
		wp_register_script( 'ifba_jquery', plugin_dir_url( __FILE__ ) . '../bower_components/jquery/dist/jquery.min.js', array( 'jquery' ) );
		wp_register_script( 'ifba_codebird', plugin_dir_url( __FILE__ ) . '../bower_components/codebird-js/codebird.js', array( 'jquery' ) );
		wp_register_script( 'ifba_doT', plugin_dir_url( __FILE__ ) . '../bower_components/doT/doT.min.js', array( 'jquery' ) );
		wp_register_script( 'ifba_moment', plugin_dir_url( __FILE__ ) . '../bower_components/moment/min/moment.min.js', array( 'jquery' ) );
		wp_register_script( 'sfbap1_fr', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/fr.js', array( 'jquery' ) );
		wp_register_script( 'ifba_socialfeed', plugin_dir_url( __FILE__ ) . '../js/jquery.socialfeed.js', array( 'jquery' ) );
		wp_register_style( 'ifba_socialfeed_style', plugin_dir_url( __FILE__ )  . '../css/jquery.socialfeed.css', false, '1.0.0' );

		wp_enqueue_style( 'ifba_jquery');
		wp_enqueue_style( 'ifba_socialfeed_style');
		wp_enqueue_style( 'ifba_fontawesome_style');
   		wp_enqueue_script( 'ifba_codebird');
   		wp_enqueue_script( 'ifba_doT');
   		wp_enqueue_script( 'ifba_moment');
   		wp_enqueue_script( 'ifba_socialfeed');

   			wp_register_script( 'ifba_en', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/en-ca.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba_en');
		/*
			wp_register_script( 'ifba_ar', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/ar.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba_ar');
			wp_register_script( 'ifba_bn', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/bn.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba_bn');
				
			wp_register_script( 'ifba-cs', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/cs.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-cs');
			wp_register_script( 'ifba-da', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/da.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-da');
			wp_register_script( 'ifba-nl', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/nl.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-nl');
			wp_register_script( 'ifba-fr', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/fr.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-fr');
			wp_register_script( 'ifba-de', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/de.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-de');
			wp_register_script( 'ifba-it', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/it.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-it');
			wp_register_script( 'ifba-ja', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/ja.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-ja');
			wp_register_script( 'ifba-ko', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/ko.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-ko');
			wp_register_script( 'ifba-pt', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/pt.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-pt');
			wp_register_script( 'ifba-ru', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/ru.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-ru');
			wp_register_script( 'ifba-es', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/es.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-es');
			wp_register_script( 'ifba-tr', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/tr.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-tr');
			wp_register_script( 'ifba-uk', plugin_dir_url( __FILE__ ) . '../bower_components/moment/locale/uk.js', array( 'jquery' ) );
	   		wp_enqueue_script( 'ifba-uk');
*/
}


function ifba_admin_enqueue_styles() {
	
		wp_enqueue_script('jquery');
		wp_register_script( 'ifba_script', plugin_dir_url( __FILE__ ) . '../js/ifba-script.js', array( 'jquery' ) );
		wp_enqueue_script( 'ifba_script');
		
}