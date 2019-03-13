<?php
defined('ABSPATH') or die('No tienes permiso para hacer eso');
/*
 *  Métodos para crear la tabla con los formularios del panel de administración.
 */

// Si la clase WP_List_Table no ha sido cargada la insertamos
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

// Clase secundaria que extiende de 'WP_List_Table'
class manusoft_cf2pdf_forms_list_table extends WP_List_Table {

  function get_columns() {
    $columns = array (
      'cb' => '<input type="checkbox" />',
      'manusoft_cf2pdf_form_name' => '<b>Nombre</b>',
      'manusoft_cf2pdf_form_total' => '<b>Registros</b>'
    );
    return $columns;
  }

  function prepare_items() {
      $columns = $this->get_columns();
      $hidden = array();
      $perPage = 5;
      $currentPage = $this->get_pagenum();
      $count_forms = wp_count_posts('wpcf7_contact_form');
      $totalItems  = $count_forms->publish;

      $this->set_pagination_args(array(
          'total_items' => $totalItems,
          'per_page'    => $perPage
      ));

      $sortable = array();

      $this->_column_headers = array($columns, $hidden, $sortable);
      $this->items = manusoft_cf2pdf_get_all_forms($perPage, $currentPage);
      $this->process_bulk_action();
  }

  function get_bulk_actions() {
      $actions = array(
          'delete' => 'Eliminar'
      );
      return $actions;
  }

  function process_bulk_action() {
      //Detect when a bulk action is being triggered...

  }

  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="forms[]" value="%s" />',
          $item['id']
      );
  }

  function column_default($item,$column_name) {
    switch ($column_name) {
      case 'manusoft_cf2pdf_form_name':
        return '<b><a href="">'.$item['name'].'</a></b>';
      case 'manusoft_cf2pdf_form_total':
        return $item['total'];
      default:
        return print_r($item,true); //Show the whole array for troubleshooting purposes
    }
  }

}
?>
