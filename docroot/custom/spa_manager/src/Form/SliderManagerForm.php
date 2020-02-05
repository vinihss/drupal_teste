<?php

namespace Drupal\spa_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class SliderManagerForm
 * @package Drupal\spa_manager\Form
 */
class SliderManagerForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'slidermanager_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            $query = $conn->select('slider_item', 'm')
                ->condition('id', $_GET['num'])
                ->fields('m');
            $record = $query->execute()->fetchAssoc();

        }

        $form['titulo'] = array(
            '#type' => 'textfield',
            '#title' => t('Título:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['titulo']) && $_GET['num']) ? $record['titulo']:'',
        );

        $form['link'] = array(
            '#type' => 'textfield',
            '#title' => t('Link:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['titulo']) && $_GET['num']) ? $record['titulo']:'',
        );

        $form['texto'] = array(
            '#type' => 'textarea',
            '#title' => t('Texto:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['texto']) && $_GET['num']) ? $record['texto']:'',
        );

        $form['image'] = array(
                '#type' => 'managed_file',
                '#title' => t('Imagem'),
                '#description' => t('Imagem no formato png, jpg ou gif'),
                '#upload_validators' => array(
                    'file_validate_extensions' => array('gif png jpg jpeg'),
                    'file_validate_size' => array(25600000),
                ),
                '#upload_location' => 'public://slider_images',
                '#required' => TRUE,
        );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save',
            //'#value' => t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $query = \Drupal::database()->select('slider_item', 'm');
        $query->fields('m', ['id','titulo', 'link','texto','imagem']);
        $results = $query->execute()->fetchAll();
        if (count($results) >= 6) {
            $form_state->setErrorByName('slide', t('Quantidade máxima de slides ultrapassada: 6'));
        }
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $field=$form_state->getValues();

        $imagem = File::load($field['image'][0]);

        /* Set the status flag permanent of the file object */
        $imagem->setPermanent();

        $field = array(
            'titulo' => $field['titulo'],
            'link' => $field['link'],
            'texto' => $field['texto'],
            'imagem' => $imagem->id()
        );

        if (isset($_GET['num'])) {

            $query = \Drupal::database();
            $query->update('slider_item')->fields($field)->condition('id', $_GET['num'])->execute();
            drupal_set_message("Atualizado com sucesso!");
            $form_state->setRedirect('spa_manager.slider_manager_table');

        } else {

            $query = \Drupal::database();
            $query ->insert('slider_item')->fields($field)->execute();

            drupal_set_message("Salvo com sucesso!");

            $response = new RedirectResponse("/spamanager/slidermanager/table");
            $response->send();
        }
    }
}
