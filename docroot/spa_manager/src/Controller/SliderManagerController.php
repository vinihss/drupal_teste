<?php

namespace Drupal\spa_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\spa_manager\Controller
 */
class SliderManagerController extends ControllerBase {


    public function getContent() {
        // First we'll tell the user what's going on. This content can be found
        // in the twig template file: templates/description.html.twig.
        // @todo: Set up links to create nodes and point to devel module.
        $build = [
            'description' => [
                '#theme' => 'mydata_description',
                '#description' => 'foo',
                '#attributes' => [],
            ],
        ];
        return $build;
    }

    /**
     * Display.
     *
     * @return string
     *   Return Hello string.
     */
    public function display() {

        //create table header
        $header_table = array(
            'id'=>    t('ID'),
            'titulo' => t('Título'),
            'imagem' => t('Imagem'),
            '',
            ''
        );

        $query = \Drupal::database()->select('slider_item', 'm');
        $query->fields('m', ['id','titulo','descricao','imagem']);
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach($results as $data){
            $delete = Url::fromUserInput('/spamanager/form/delete/'.$data->id);
            $edit   = Url::fromUserInput('/spamanager/form/slider/?num='.$data->id);
            $file = \Drupal\file\Entity\File::load($data->imagem);
            if (!empty($file)) {
                $path = $file->getFileUri();
            }
            //print the data from table
            $rows[] = array(
                'id' =>$data->id,
                'titulo' => $data->titulo,
                'imagem' => new FormattableMarkup("<img style='width:120px' src='@path'/>", ['@path' => file_create_url($path)]),
                \Drupal::l('Remover', $delete),
                \Drupal::l('Editar', $edit),
            );
        }

        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('Nenhum item adicionado'),
        ];

        return $form;

    }

}
