<?php

namespace Drupal\spa_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\spa_manager\Controller
 */
class SliderManagerController extends ControllerBase {

    /**
    * Display the markup.
    *
    * @return array
    *   Return markup array.
    */
    public function content() {
        $links = [];
        $links[] = [
            'titulo' => 'Adicionar slide',
            'url' => Url::fromRoute('spa_manager.slider_manager_form')
        ];
        $links[] = [
            'titulo' => 'Listar slides',
            'url' => Url::fromRoute('spa_manager.slider_manager_table')
        ];

        return [
            '#theme' => 'config-slide-block',
            '#links' => $links
        ];
    }


    /**
     * Display.
     *
     * @return string
     *   Return table.
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
        $query->fields('m', ['id','titulo','link','texto','imagem']);
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
