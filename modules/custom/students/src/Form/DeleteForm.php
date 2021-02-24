<?php


namespace Drupal\students\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;


/**
 * Class DeleteForm
 * @package Drupal\mymodule\Form
 */
class DeleteForm extends ConfirmFormBase
{

  public $id;

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'delete_form';
  }

  public function getQuestion()
  {
    return t('Delete student');
  }

  public function getCancelUrl()
  {
    return new Url('students.content');
  }

  public function getDescription()
  {
    return t('Do you want to delete student with id: %id ?', array('%id' => $this->id));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText()
  {
    return t('Delete it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText()
  {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
  {

    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $query = \Drupal::database();
    $query->delete('students')
      ->condition('id', $this->id)
      ->execute();
    $response = new AjaxResponse();
    \Drupal::messenger()->addStatus('Succesfully deleted.');
    $form_state->setRedirect('students.content');
    return $response;
  }
}
