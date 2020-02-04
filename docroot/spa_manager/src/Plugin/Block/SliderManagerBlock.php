<?php

namespace Drupal\spa_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Slidae Manager' block.
 *
 * @Block(
 *  id = "slidermanager_block",
 *  admin_label = @Translation("Gerenciador do Slider"),
 * )
 */
class SliderManagerBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        $form = \Drupal::formBuilder()->getForm('Drupal\spa_manager\Form\SliderManagerForm');
        return [
            '#theme' => 'slider-block',
            '#form' => $form
        ];
    }

}
