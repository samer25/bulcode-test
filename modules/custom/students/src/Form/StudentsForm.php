<?php
/**
 * @file
 * Contains \Drupal\students\Form\StudentsForm
 */

namespace Drupal\students\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides data of the student
 */
class StudentsForm extends FormBase
{
  /**
   * (@inheritdoc)
   */
  public function getFormId()
  {
    // TODO: Implement getFormId() method.
    return 'students_name_form';
  }

  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // TODO: Implement buildForm() method.
    $conn = Database::getConnection();
    $data = array();
    if (isset($_GET['id'])) {
      $query = $conn->select('students', 'm')
        ->condition('id', $_GET['id'])
        ->fields('m');
      $data = $query->execute()->fetchAssoc();
    }
    $form['name'] = array(
      '#title' => $this -> t('Name'),
      '#type' => 'textfield',
      '#size' => 25,
      '#default_value' => (isset($data['name'])) ? $data['name'] : '',
      '#description' => $this->t("name of the students"),
      '#required' => TRUE,
    );

    $form['gender'] = array(
      '#title' => $this->t('Gender'),
      '#type' => 'radios',
      '#options' => array('Male' => 'Male', 'Female' => 'Female'),
      '#default_value' => (isset($data['gender'])) ? $data['gender'] : '',
      '#description' => $this->t("gender of the students"),
      '#required' => TRUE,
    );

    $form['facultynumber'] = array(
      '#title' => $this->t('Faculty Number'),
      '#type' => 'textfield',
      '#size' => 8,
      '#default_value' => (isset($data['facultynumber'])) ? $data['facultynumber'] : '',
      '#description' => $this->t("Faculty number of the students"),
      '#required' => TRUE,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Add'),
    );
    return $form;
  }

  /**
   * (@inheritdoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('facultynumber');
    if (!is_numeric($value)) {
      $form_state->setErrorByName('facultynumber', t('Must contain only digits!'));
    }
    if (strlen($value) != 8) {
      $form_state->setErrorByName('facultynumber', t('Must contain exactly 8 digits!'));

    }
  }

  /**
   * (@inheritdoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
//    \Drupal::database()->insert('students')
//      ->fields(array(
//        'name' => $form_state->getValue('name'),
//        'gender' => $form_state->getValue('gender'),
//        'facultynumber' => $form_state->getValue('facultynumber'),
//      ))
//      ->execute();
    $data = array(
      'name' => $form_state->getValue('name'),
      'facultynumber' => $form_state->getValue('facultynumber'),
      'gender' => $form_state->getValue('gender'),

    );
    if (isset($_GET['id'])) {
      // update data in database
      \Drupal::database()->update('students')->fields($data)->condition('id', $_GET['id'])->execute();
    } else {
      // insert data to database
      \Drupal::database()->insert('students')->fields($data)->execute();
    }

    \Drupal::messenger()->addStatus('Succesfully saved');
    $url = new Url('students.content');
    $response = new RedirectResponse($url->toString());
    $response->send();

  }
}
