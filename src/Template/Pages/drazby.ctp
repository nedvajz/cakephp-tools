<?php
foreach ($data as $entity) {
    echo $this->Html->div('drazba', implode([
        $this->Html->link($entity->url, $entity->url),
        $entity->html,
        '<hr/>'
    ]));
}
