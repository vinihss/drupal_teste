<?php

namespace Drupal\spa_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "spamanager_block",
 *   admin_label = @Translation("SPA Manager"),
 *   category = @Translation("SPA Manager"),
 * )
 */
class SpaManagerBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        return [
            '#markup' => $this->t('Hello, World!'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        $default_config = \Drupal::config('spa_manager.settings');
        return [
            'spa_manager_name' => $default_config->get('spa_manager.name'),
        ];
    }

}