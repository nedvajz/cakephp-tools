<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PropertyController extends AppController
{

    public $paginate = [
        'limit' => 20,
        'order' => [
            'Portaldrazeb.datum_drazby' => 'ASC'
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $this->Portaldrazeb = TableRegistry::get('Portaldrazeb');

        $okres = $this->Portaldrazeb->find('list', [
            'keyField' => 'okres',
            'valueField' => 'okres',
        ])->toArray();
        $okres = array_filter($okres);

        $conditions = [
            'Portaldrazeb.datum_drazby >' => date('Y-m-d H:i:s')
        ];
        if (isset($this->request->query['filterokres'])) {
            $conditions['Portaldrazeb.okres'] = $this->request->query['filterokres'];
        }

        $this->paginate['conditions'] = $conditions;

        $data = $this->paginate($this->Portaldrazeb);
        $this->set(compact('data', 'okres'));
    }
}
