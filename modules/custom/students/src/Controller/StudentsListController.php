<?php

/**
 * @file
 * Contains \Drupal\students\Controller\MyModuleController.
 */

namespace Drupal\students\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class StudentsListController extends ControllerBase
{
  public function content()
  {
    $header_table = array(
      'id' => t('ID'),
      'Name' => t('Name'),
      'Gender'=> t('Gender'),
      'Faculty number' => t('Faculty Number'),
      'delete' => t('Delete'),
      'edit' => t('Edit'),
    );


    // get data from database
    $query = \Drupal::database()->select('students', 'm');
    $query->fields('m', ['id', 'name','gender', 'facultynumber',]);
    $results = $query->execute()->fetchAll();
    $rows = array();
    foreach ($results as $data) {
      $url_delete = Url::fromRoute('students.delete_form', ['id' => $data->id], []);
      $url_edit = Url::fromRoute('students.form', ['id' => $data->id], []);
      $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
      $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);

      //get data
      $rows[] = array(
        'id' => $data->id,
        'name' => $data->name,
        'gender' => $data->gender,
        'facultynumber' => $data->facultynumber,
        'delete' => $linkDelete,
        'edit' =>  $linkEdit,
      );

    }
    // render table
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No student data are added yet.'),
    ];
    return $form;

  }
}

