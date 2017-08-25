<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Portaldrazeb Entity
 *
 * @property int $id
 * @property string $jednaci_cislo
 * @property string $url
 * @property \Cake\I18n\FrozenTime $created
 * @property int $cena_podani
 * @property \Cake\I18n\FrozenTime $datum_drazby
 * @property string $misto_drazby
 * @property string $okres
 * @property string $adresa
 * @property int $cena_znalec
 * @property int $jistina
 * @property string $jistina_kam
 * @property string $html
 */
class Portaldrazeb extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
