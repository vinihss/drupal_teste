<?php

namespace Drupal\spa_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
/**
 * Class DeleteForm.
 *
 * @package Drupal\spa_manager\Form
 */
class SliderManagerDeleteForm extends ConfirmFormBase {


    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'delete_form';
    }

    public $cid;

    public function getQuestion() {
        return t('Remover o slide %cid?', array('%cid' => $this->cid));
    }

    public function getCancelUrl() {
        return new Url('spa_manager.slider_manager_table');
    }
    public function getDescription() {
        return t('Confirmar a operação!');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return t('Confirmar');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
        return t('Cancelar');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {

        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        try {
            $query = \Drupal::database();
            $query->delete('slider_item')
                ->condition('id', $this->id)
                ->execute();
            drupal_set_message("Removido com sucesso");
            $form_state->setRedirect('spa_manager.slider_manager_table');
        } catch (\Exception $e) {
            drupal_set_message("Não foi possível remover o slide");
        }

    }
}
