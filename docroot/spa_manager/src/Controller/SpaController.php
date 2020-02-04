<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 30/01/20
 * Time: 19:43
 */

namespace Drupal\spa_manager\Controller;

use Drupal\Core\Controller\ControllerBase;

class SpaController extends ControllerBase
{
    /**
     * Display the markup.
     *
     * @return array
     *   Return markup array.
     */
    public function content() {
        return [
            '#type' => 'markup',
            '#markup' => $this->t('Hello, World!!'),
        ];
    }

}