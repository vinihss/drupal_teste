<?php
/**
 * @file
 *
 * A custom slider block
 * */
namespace Drupal\spa_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 * Provides a Slider Block.
 *
 * @Block(
 *   id = "spa_slider_block",
 *   admin_label = @Translation("Slider Block"),
 *   category = @Translation("Slider Block"),
 * )
 */
class SliderBlock extends BlockBase implements BlockPluginInterface {


    /**
     * {@inheritdoc}
     */
    public function build() {

        $query = \Drupal::database()->select('slider_item', 'm');
        $query->fields('m', ['id','titulo', 'link','texto','imagem']);
        $results = $query->execute()->fetchAll();
        $rows = array();

        foreach($results as $data){

            $file = \Drupal\file\Entity\File::load($data->imagem);
            if (!empty($file)) {
                $path = $file->getFileUri();
            }

            $rows[] = array(
                'titulo' => $data->titulo,
                'link' => $data->link,
                'texto' => $data->texto,
                'imagem' => file_create_url($path)
            );
        }

        return [
            '#theme' => 'slider-block',
            '#rows' => $rows
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function blockForm($form,  $form_state) {
        $form = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form,  $form_state) {
        parent::blockSubmit($form, $form_state);
    }
}