<?php

namespace ToolsParty\Chance;

/**
 * Class Chance
 * @package ToolsParty\Chance
 */
class Chance
{
    /**
     * @var array
     * Для хранения количества вызовов каждого ключа
     */
    protected $stackKeys = [];

    /**
     * @var int
     * Для хранения общего кол-ва вызовов getMember
     */
    protected $stackMember = 0;

    /**
     * @var array
     * Для хранения ключей в качестве значений
     * + Для быстрого и удобного обращения по целочисленному индексу
     */
    protected $keys = [];

    /**
     * @var array
     * Для хранения реального значения вероятности возврата того или иного ключа
     */
    protected $chances = [];

    /**
     * @var int
     * Количество ключей, max дял mt_rand
     */
    protected $dataCount;

    /**
     * Chance constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        //выбираем ключи
        $this->keys = array_keys($data);
        //выбираем шансы
        $this->chances = array_values($data);

        //общая сумма
        $total = array_sum($data);

        //вычисляем реальные пропорции, исходя из того что $total - это 100%
        array_walk($this->chances, function (&$value, $key, $total) {
            $value = $value / $total;
        }, $total);

        //Количество ключей
        $this->dataCount = count($data) - 1;
    }

    /**
     * @return mixed
     */
    public function getMember()
    {
        //инкремент
        $this->stackMember++;
        //случайно определяем индекс
        $call = mt_rand(0, $this->dataCount);

        //проверяем был ли возвращен этот индекс
        if (!isset($this->stackKeys[$call])) {
            //если первый вызов
            $this->stackKeys[$call] = 1;
            return $this->keys[$call];
        } else {
            //сравниваем отношение кол-ва вызовов текущего индекса к кол-ву всех вызовов метода
            //с максимально допустимым значением для этого индекса
            if (($this->stackKeys[$call]) / $this->stackMember >= $this->chances[$call]) {
                //если больше или равно
                //находим индекс с нибольшим значением
                $call = array_search(max($this->chances), $this->chances);
                //вдруг этот индекс еще не был ни разу возвращен или инкремент
                isset($this->stackKeys[$call]) ? $this->stackKeys[$call]++ : $this->stackKeys[$call] = 1;
                return $this->keys[$call];
            } else {
                //инкремент и return
                $this->stackKeys[$call]++;
                return $this->keys[$call];
            }
        }
    }
}