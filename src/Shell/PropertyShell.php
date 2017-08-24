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
        $response = $http->post('http://www.portaldrazeb.cz/index.php?p=search', (string) $data);

        $response = $http->get('http://www.portaldrazeb.cz/vyhledavani');

        // Parsing
        $dom = HtmlDomParser::str_get_html($response->body);
        
        foreach ($dom->find('div[class=work_right_popis]') as $element) {
            $nadpis = $element->children(0);
            $title = $nadpis->text();
            debug($title);
            // debug($element->text());
            // code...
        }

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
