<?php

namespace App;

require_once __DIR__. '/vendor/autoload.php';


class PdoAdapterFake implements DatabaseAdapterInterface{
    private $currentTable="";
    private $conditionsKey="";
    private $conditionsValue="";

    private $db = [
              'users'=>[
                    ['id'=>1,'name'=>'Rachel','email'=>'username@domain.com','role'=>'Guest'],
                    ['id'=>2,'name'=>'Kok','email'=>'email@email.com','role'=>'Administrator']
                 ]];

    public function select($table,$array){
        $this->currentTable=$table;
        $this->conditionsKey = array_key_first($array);
        $this->conditionsValue = array_values($array)[0];
    }

    public function fetch(){
        $arr = $this->db[$this->currentTable];
        foreach ($arr as  $v){
            foreach ($v as $key=>$value){
            if($key == $this->conditionsKey){
                if($value == $this->conditionsValue){
                    return $v;
                }
            }}
        }
    }

    public function fetchAll(){
        $arr = $this->db[$this->currentTable];
        $result = [];
        foreach ($arr as  $v){
            foreach ($v as $key=>$value){
                if($key == $this->conditionsKey){
                    if($value == $this->conditionsValue){
                        array_push($result,$v);
                    }
                }}
        }
        return $result;
    }
}

$adapter = new PdoAdapterFake(/*"mysql:dbname=users", "myfancyusername", "mysecretpassword"*/);
$userRepository = new UserRepository(new UserMapper($adapter, new UserCollection()));

$users = $userRepository->fetchByName("Rachel");

foreach ($users as $user) {
    echo $user->getName() . " " . $user->getEmail() . "\n";
}

$users = $userRepository->fetchByEmail("username@domain.com");
foreach ($users as $user) {
    echo $user->getName() . " " . $user->getEmail() . "\n";
}


$administrators = $userRepository->fetchByRole("Administrator");
foreach ($administrators as $administrator) {
    echo $administrator->getName() . " " . $administrator->getEmail() . "\n";
}

$guests = $userRepository->fetchByRole("Guest");
foreach ($guests as $guest) {
    echo $guest->getName() . " " . $guest->getEmail() . "\n";
}

// Персистентные структуры данных (англ. persistent data structure) — это структуры данных, которые при внесении
// в них каких-то изменений сохраняют все свои предыдущие состояния и доступ к этим состояниям.

// В ситуации разростания моделей доменного слоя (бизнес-логики) растет и система mappers
// Т.е. поддерживать незменность струтуры данных (персистентность) сложных агрегированных корней станет сложно и долго
// а для оптимизации дублирование кода
// Репозиторий характеризует себя как говорящий на языке модели и благодаря связи с mappers игнорирует
// требование к персистентности т.е. может менятся под требования бизнес-логики
//
// Репозиторий скрывает все тонкости внедрения и обработки Data Mappers за упрощенным API-интерфейсом

// Maper - учитывая, что модель предметной области должна полностью игнорировать базовое хранилище, установленное в инфраструктуре,
// следующий логический шаг, который мы должны предпринять, - это реализовать слой сопоставления mappers , который будет хорошо отделять его от базы данных.







// Персистентность (т.е. не подверженность изменению ) архитектурного слоя поддрживает Repository за счет того что он берет на себя все изменения/новые требования бизнес-правил
// и в итоге обьекты DataMapers остаются неизменны

/*
 * Есть простые обьекты php предметной области/бизнес-логики
 * Есть mappers обьекты они занимаются сопоставлением/картограф/отображением обьектов предметной области на базу данных и обратно поиск в базе и восзосдание обьекта предметной области,
 * таким образом обьекты предметной области не зависят/игнорируют  от базы данных
 * Но использовать mappers обьекта в слое бизнес-логики это зазразнение логики издежками инфраструктуры
 * А что если бизнес-правила станут еще извилистей и потребуют более детализированных запросов характерных для бизне-правил?
 * Тогда придется расширять обьект mappers т.е. впихнуть бизнес правила в обьект отображения базы данных в обьект php!
 * Репозиторий является еще одним слоем абстракции над mappers обьектом и берет на себя все изменения/условия бизнес-правил для реализации их требоаний,
 * являясь единой точкой входа для логики приложения к данным.
 * Репозиторий эффективно обменивает бизнес-терминологию с клиентским кодом (так называемый универсальный язык, придуманный Эриком Эвансом в его книге « Дизайн, управляемый доменом» )
 *
*/

$adapter = new PdoAdapterFake();
$userMapper = new UserMapper($adapter, new UserCollection());

$users = $userMapper->fetchAll(array("name" => "Rachel"));
foreach ($users as $user) {
    echo $user->getName() . " " . $user->getEmail() . "\n";
}