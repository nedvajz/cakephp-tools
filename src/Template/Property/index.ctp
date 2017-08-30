<?php
echo $this->Html->div('filter', implode('', [
    $this->Form->create(null, ['method' => 'GET']),
    $this->Form->controls(
        [
        'filterokres' => [
            'label' => false,
            'options' => $okres,
            'value' => isset($this->request->query['filterokres'])? $this->request->query['filterokres']: null,
            'empty' => 'Okres'
        ]
        ],
        [
            'fieldset' => false,
            'legend' => false,
        ]
    ),
    $this->Form->submit('Filter'),
    $this->Form->end(),
]));
echo $this->Html->div('', implode(' | ', [
    $this->Paginator->sort(
        'datum_drazby',
        'Datum drazby',
        ['escape' => false]
    ),
    $this->Paginator->sort(
        'cena_podani',
        'Nejnizsi podani',
        ['escape' => false]
    ),
    $this->Paginator->sort(
        'okres',
        'Okres',
        ['escape' => false]
    ),
]));
foreach ($data as $entity) {
    echo $this->Html->div('drazba', implode([
        $this->Html->link($entity->url, $entity->url),
        $entity->html,
        '<hr/>'
    ]));
}

$next = '';
if ($this->Paginator->hasNext()) {
    $next = $this->Paginator->next();
}

$prev = '';
if ($this->Paginator->hasPrev()) {
    $prev = $this->Paginator->prev();
}

echo $this->Html->div('pager', implode([
    $prev,
    $next,
    $this->Paginator->counter([
        'format' => 'range'
    ]),
    $this->Paginator->limitControl()
]));
