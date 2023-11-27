<?php
// starships-table.php

$table_html = '<table role="table">';
$table_html .= '<thead role="rowgroup">';
$table_html .= '<tr>';
$table_html .= '<th>' . __('Name', 'starships-textdomain') . '</th>';
$table_html .= '<th>' . __('Starship Class', 'starships-textdomain') . '</th>';
$table_html .= '<th>' . __('Crew', 'starships-textdomain') . '</th>';
$table_html .= '<th>' . __('Cost in Credits', 'starships-textdomain') . '</th>';
$table_html .= '</tr>';
$table_html .= '</thead>';
$table_html .= '<tbody>';

foreach ($starships->results as $starship) {
    $table_html .= '<tr role="row">';
    $table_html .= '<td role="cell">' . esc_html($starship->name) . '</td>';
    $table_html .= '<td role="cell">' . esc_html($starship->starship_class) . '</td>';
    $table_html .= '<td role="cell">' . esc_html($starship->crew) . '</td>';
    $table_html .= '<td role="cell">' . esc_html($starship->cost_in_credits) . '</td>';
    $table_html .= '</tr>';
}

$table_html .= '</tbody>';
$table_html .= '</table>';

echo $table_html;
