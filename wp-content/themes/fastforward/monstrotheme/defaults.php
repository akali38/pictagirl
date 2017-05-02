<?php
ob_start();
include get_template_directory() . '/json/defaults.json';
$defaults = json_decode(ob_get_clean());
$defaults->header->logo->url = get_template_directory_uri() . '/images/logo.png';
update_option(MONSTROTHEME_SETTINGS, $defaults);