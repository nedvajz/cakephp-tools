<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

/**
 * Portaldrazeb Model
 *
 * @method \App\Model\Entity\Portaldrazeb get($primaryKey, $options = [])
 * @method \App\Model\Entity\Portaldrazeb newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Portaldrazeb[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Portaldrazeb|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Portaldrazeb patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Portaldrazeb[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Portaldrazeb findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PortaldrazebTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('portaldrazeb');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('jednaci_cislo');

        $validator
            ->scalar('url')
            ->notEmpty('url')
            ->add('url', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table'
                ]
            ]);

        $validator
            ->integer('cena_podani')
            ->allowEmpty('cena_podani');

        $validator
            ->dateTime('datum_drazby')
            ->allowEmpty('datum_drazby');

        $validator
            ->scalar('misto_drazby')
            ->allowEmpty('misto_drazby');

        $validator
            ->scalar('okres')
            ->allowEmpty('okres');

        $validator
            ->scalar('adresa')
            ->allowEmpty('adresa');

        $validator
            ->integer('cena_znalec')
            ->allowEmpty('cena_znalec');

        $validator
            ->integer('jistina')
            ->allowEmpty('jistina');

        $validator
            ->scalar('jistina_kam')
            ->allowEmpty('jistina_kam');

        $validator
            ->allowEmpty('html');

        return $validator;
    }
}
