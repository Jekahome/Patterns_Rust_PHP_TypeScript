<?php
declare(strict_types=1);
// DataMaper

/*
 * Преобразователь Данных — это паттерн, который выступает в роли посредника для двунаправленной передачи
 * данных между постоянным хранилищем данных (часто, реляционной базы данных) и представления данных в памяти
 * (слой домена, то что уже загружено и используется для логической обработки). Цель паттерна в том,
 * чтобы держать представление данных в памяти и постоянное хранилище данных независимыми друг от друга и от самого преобразователя данных.
 */
class User
{
    private $username;
    private $email;
    public static function fromState(array $state): User{
        return new self(
            $state['username'],
            $state['email']
        );}

    public function __construct(string $username, string $email){
        $this->username = $username;
        $this->email = $email;}

    public function getUsername(){
        return $this->username;}

    public function getEmail(){
        return $this->email;}
}

class StorageAdapter
{
    private $data = [];
    public function __construct(array $data){
        $this->data = $data;
    }
    public function find(int $id){
        if (isset($this->data[$id])) return $this->data[$id];
        return null;
    }
}

// Посредник между базой данных т.е. StorageAdapter и обьектов данных в памяти т.е. User
class UserMapper
{
    private $adapter;
    public function __construct(StorageAdapter $storage){
        $this->adapter = $storage;}

    public function findById(int $id): User{
        $result = $this->adapter->find($id);
        if ($result === null) throw new \InvalidArgumentException("User #$id not found");
        return $this->buildUser($result);
    }

    private function buildUser(array $row): User{
        return User::fromState($row);}
}




$storage = new StorageAdapter([1 => ['username' => 'domnikl', 'email' => 'liebler.dominik@gmail.com']]);
$mapper = new UserMapper($storage);
$user = $mapper->findById(1);

if(User::class && $user)echo 1;




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
class UserRepository
{
    private $persistence;
    public function __construct(UserMapper $persistence){
        $this->persistence = $persistence;
    }

    public function findById(int $id): User{
        return  $this->persistence->findById($id);
    }
}


$storage = new StorageAdapter([1 => ['username' => 'domnikl', 'email' => 'liebler.dominik@gmail.com']]);
$mapper = new UserMapper($storage);
$repository = new UserRepository($mapper);
$user = $repository->findById(1);
if(User::class && $user)echo 1;
