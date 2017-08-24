<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->scalar('html')
            ->allowEmpty('html');

        return $validator;
    }
}
