<?php

//plugin info

/*
 * Plugin Name:       PLot data plugin
 * Description:       Plot the data of 'data' field in the db table  
 * Version:           1.0.0
 * Author:            Shatha 
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_menu', 'wporg_options_page'); //a hook function



global $csv_file;

global $textFilePath;
function wporg_options_page() {
     add_menu_page(
         'Plot Data',       // Page title
         'Plot Data',       // Menu title
         'manage_options',  // Capability required to access the menu
         'custom-options',  // URL query (slug)
         'custom_options_page_callback',    // function to display content
         'dashicons-chart-line',  // Icon for the menu item
         20  // Menu position
     );

     add_submenu_page(
        'custom-options',  // Parent menu slug
        'Submenu Page',    // Sub-menu page title
        'Export to csv',         // Sub-menu text
        'manage_options',  // Capability required to access the sub-menu
        'custom-submenu',  // Sub-menu slug (used in URLs)
        'csv_data',
    );
 }
 
 function custom_options_page_callback() {
    // Content of your custom options page
    echo '<div class="wrap">';
    echo '<h1>Plot Data</h1>';

    // Call a function to generate and display the table
    custom_display_data_table();

    echo '</div>';
}

function custom_display_data_table() {
    global $wpdb;
    // Generate and display the table content
    echo '<table class="wp-list-table widefat">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Date & Time</th>';
    echo '<th>Email</th>';
    echo '<th>Member ID</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $rows =$wpdb->get_results("SELECT data FROM wp_cptslotsbk_messages"); //data    
    //$tyt = explode(" ", $rows[2]->data);

    
    //$textFilePath='tbl_data.txt';
    foreach ($rows as $row) {

        $tyt = explode(" ", $row->data);

        $appointments_pos = array_search('Appointments:', $tyt);
        $email_pos = array_search('Email:', $tyt);
        $id_pos = array_search('memberId:', $tyt);

        $prt_appointment = $tyt[$appointments_pos + 2];
        $prt_email = str_replace('memberId:', '',$tyt[$email_pos+5]);
        $prt_id = $tyt[$id_pos + 6];
 
        echo '<tr>';
        echo '<td>' . $prt_appointment . '</td>';
        echo '<td>' . $prt_email . '</td>';
        echo '<td>' . $prt_id . '</td>';
        echo '</tr>';

        $data_array = array('Appointment', 'Email', 'Member Id');

        $data_array = array($prt_appointment, $prt_email, $prt_id);

        $csv_file = 'data.csv';
        $csvFile = fopen($csv_file, 'a');
        
        fputcsv($csvFile, $data_array);
        
        // $dataLine = $prt_appointment . ',' . $prt_email . ',' . $prt_id . "\n";
        // $handle = fopen($textFilePath, 'a') or die("Unable to open file!");
        // fwrite($handle, $dataLine);
        // fclose($handle);

    }
    fclose($csvFile);

    echo '</tbody>';
    echo '</table>';
}


function csv_data($data_array){
    echo '<a href="data.csv">Click here to download the csv file</a>';

}