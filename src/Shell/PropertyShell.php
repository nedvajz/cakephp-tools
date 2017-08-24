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
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Sunra\PhpSimple\HtmlDomParser;
use Cake\Http\Client;
use Cake\Http\Client\FormData;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class PropertyShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return int|null
     */
    public function main()
    {
    }

    /**
     * Method: parsePortaldrazeb
     *
     * @return void
     */
    public function parsePortaldrazeb()
    {

        $data = new FormData();
        $data->add('posted', 1);
        $data->add('shiden', 1);
        $data->add('ktg', 1);
        $data->add('hledej_podle_nazvu', '');

        // Predmet drazby
        $data->add('p1', 1); //pozemek
        $data->add('p2', 1); //stavebni pozemek
        $data->add('p3', 1); //rodinny dum
        $data->add('p4', 1); //rekreacni objekt
        $data->add('p5', 1); //bytovy dum
        $data->add('p6', 1); //byt
        $data->add('p7', 1); //nebytovy / kancelar
        $data->add('p8', 1); //prumyslova stavba
        $data->add('p9', 1); //zemedelska stavba
        $data->add('p10', 1); //rozestavena stavba
        $data->add('p11', 1); //clensky podil

        // Lokalita
        $data->add('k4', 1); // Hradec Kralove
        $data->add('k5', 1); // Pardubice

        $data->add('orderc', 1);
        $data->add('ordert', 2);

        $http = new Client();
        $http->post('http://www.portaldrazeb.cz/index.php?p=search', (string) $data);

        $response = $http->get('http://www.portaldrazeb.cz/vyhledavani');

        // Parsing
        $dom = HtmlDomParser::str_get_html($response->body);

        $table = TableRegistry::get('Portaldrazeb');
        
        $drazby = [];
        foreach ($dom->find('div[class=work_right_popis] a') as $key=>$element) {
            $url = $element->href;
            $currentResponse = $http->get($url);
            $currentDom = HtmlDomParser::str_get_html($currentResponse->body);
            $detail = $currentDom->find('div[class=right_work]', 0)->find('div[class=detail]', 0);

            $drazba = '';
            $drazba .= '<div>';
            $drazba .= '<h2>' . ($key+1) . '</h2>';
            $drazba .= $detail;
            $drazba .= '</div>';

            $entity = $table->newEntity();
            $entity->html = $detail;
            $entity->url = $url;

            if ($table->save($entity)) {
                $this->out('Drazba saved');
            }

            $drazby[] = $drazba;
        }
        // $email = new Email();
        // $email
        //     ->emailFormat('html')
        //     ->to('ondrej.nedvidek@gmail.com')
        //     ->from('ondra@ondra.me')
        //     ->subject('ondra.me - drazby')
        //     ->send(implode($drazby));

        $this->out('Done');
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('parsePortaldrazeb');
        $parser->setDescription(
            'Parses www.portaldrazeb.cz for Hradec Kralove'
        );

        return $parser;
    }
}
