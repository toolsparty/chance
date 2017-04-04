# Chance

значения с заданой вероятностью

## Установка

> composer require toolsparty/chance:dev-master

## Пример

```php
use ToolsParty\Chance\Chance;

$data = [
    'a' => 1 / 3,
    'b' => 1 / 6,
    'c' => 1 / 2,
    //'d' => 1/8
];

$chance = new Chance($data);

//имитируем большое кол-во вызовов
for ($i = 0, $result = []; $i < 1000; ++$i) {
    //собираем результаты
    $result[] = $chance->getMember();
}

//сортируем результаты для того чтобы при каждом повторном запуске скрипта порядок ключей был одинаковым
sort($result);

//вывод статистики
var_dump(array_count_values($result));
```