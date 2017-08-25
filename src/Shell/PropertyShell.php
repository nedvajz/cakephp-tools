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

use \Datetime;
use Sunra\PhpSimple\HtmlDomParser;
use Cake\Http\Client;
use Cake\Http\Client\FormData;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;


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
     * Method: portaldrazeb
     *
     * @return void
     */
    public function portaldrazeb()
    {

        $labelsMap = [
            'Nejnižší podání:' => 'cena_podani',
            'Datum konání dražby:' => 'datum_drazby',
            'Místo konání dražby:' => 'misto_drazby',
            'Okres:' => 'okres',
            'Adresa objektu:' => 'adresa',
            'Cena stanovena znaleckým posudkem:' => 'cena_znalec',
            'Dražební jistota:' => 'jistina',
            'Způsob složení dražební jistoty' => 'jistina_kam',
        ];

        // Get page
        $this->http = new Client();
        $this->searchPortaldrazeb();
        $response = $this->http->get('http://www.portaldrazeb.cz/vyhledavani');
        $dom = HtmlDomParser::str_get_html($response->body);

        // Parsing
        $this->parsePortaldrazeb($dom);
        
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
     * Method: search
     *
     * @return void
     */
    protected function searchPortaldrazeb()
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


        // Get page
        $this->http->post('http://www.portaldrazeb.cz/index.php?p=search', (string) $data);
    }

    /**
     * Method: parsePortaldrazeb
     *
     * @param mixed $dom
     * @return void
     */
    protected function parsePortaldrazeb($dom) 
    {
        $table = TableRegistry::get('Portaldrazeb');
        foreach ($dom->find('div[class=work_right_popis] a') as $key=>$element) {
            $data = [];
            $url = $element->href;
            $currentResponse = $this->http->get($url);
            $currentDom = HtmlDomParser::str_get_html($currentResponse->body);
            $detail = $currentDom->find('div[class=right_work]', 0)->find('div[class=detail]', 0);

            $jednaciCislo = $detail
                ->find('div[class=work_right_detail_1]', 0)
                ->children(0)->plaintext;

            $jednaciCislo = str_replace('EX:', '', $jednaciCislo);
            $jednaciCislo = trim($jednaciCislo);
            $jednaciCislo = str_replace('&nbsp;', ' ', $jednaciCislo);
            $data['jednaci_cislo'] = trim($jednaciCislo);

            foreach ($detail->find('div[class=work_right_detail_1]', 0)->find('div[class=right_popis_left]') as $labelElement) {
                if (!isset($labelsMap[$labelElement->plaintext])) {
                    continue;
                }
                $label = $labelsMap[$labelElement->plaintext];
                $value = $labelElement->next_sibling()->plaintext;
                $value = html_entity_decode($value);
                $value = trim($value);
                switch ($label) {
                    case 'jistina':
                    case 'cena_znalec':
                    case 'cena_podani':
                        $value = str_replace('.', '', $value);
                        $data[$label] = (int) $value;
                        break;
                    
                    case 'datum_drazby':
                        $value = str_replace('v ', '', $value);
                        $value = str_replace(' h.', '', $value);
                        $value = str_replace('.', '-', $value);
                        if ($value != 'Není stanoven') {
                            $date = DateTime::createFromFormat('d-m-Y H:i', $value);
                            $value = date('Y-m-d H:i:s', $date->getTimestamp());
                        } else {
                            $value = '2099-01-01 00:00:00';
                        }
                        $data[$label] = $value;
                        break;
                    
                    default:
                        $data[$label] = $value;
                        break;
                }
            }

            $data['html'] = $detail;
            $data['url'] = $url;

            $entity = $table->newEntity($data);

            if ($table->save($entity)) {
                $this->out('Drazba saved');
            } else {
                $errors = $entity->errors();
                // Update
                if (!empty($errors['url']['unique'])) {
                    $query = $table->find('all', [
                        'conditions' => ['url' => $data['url']]
                    ]);
                    $entity = $query->first();
                    foreach ($data as $key=>$value) {
                        $entity->{$key} = $value;
                    }
                    if ($table->save($entity)) {
                        $this->out('Drazba updated');
                    }
                } else {
                    unset($data['html']);
                    $this->log($data);
                }
            }

        }

        // Next page
        $aktivni = $dom->find('a[class=aktivnistrana]', 0);
        if(!empty($aktivni)) {
            $relativeUrl = $aktivni->next_sibling()->href;
            $url = "http://www.portaldrazeb.cz$relativeUrl";

            $response = $this->http->get($url);
            $body = $response->body;
            $dom = HtmlDomParser::str_get_html($body);

            $this->parsePortaldrazeb($dom);
        }
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('portaldrazeb');
        $parser->setDescription(
            'Parses www.portaldrazeb.cz for Hradec Kralove'
        );

        return $parser;
    }
}
