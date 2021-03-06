<?php

namespace App\Model\Factory;

use CakephpFactoryMuffin\Model\Factory\AbstractFactory;
use Cake\ORM\TableRegistry;
use League\FactoryMuffin\Faker\Facade as Faker;

class ArticlesFactory extends AbstractFactory
{

    /**
     * Returns factory definition.
     *
     * @return array
     */
    public function definition()
    {
        return [
            '_recreate' => true,
            'title' => Faker::sentence(),
            'description_length' => Faker::numberBetween(3, 7),
            'description' => function ($item) {
                $paragraphs = Faker::paragraphs($item['description_length'], true);

                return $paragraphs();
            },
            'body_length' => Faker::numberBetween(2, 5),
            'body' => function ($item) {
                $paragraphs = Faker::paragraphs($item['body_length'], true);

                return $paragraphs();
            },
            'created' => Faker::dateTimeBetween('-2 year', 'now'),
            'modified' => Faker::dateTimeBetween('-2 year', 'now'),
            'tagList' => function ($item) {
                $tags = TableRegistry::getTableLocator()->get('Tags')->find()->select(['label'])
                    ->all()
                    ->shuffle()
                    ->take(5)
                    ->map(function ($i) {
                        return $i['label'];
                    })
                    ->reduce(function ($accum, $item) {
                        return ($accum ? $item . ' ' . $accum : $item);
                    });

                return $tags;
            }
        ];
    }
}
