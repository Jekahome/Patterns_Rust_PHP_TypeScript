<?php

require __DIR__.'/vendor/autoload.php';

//  Первый подход возлагает на UOW прямую ответственность за регистрацию или постановку
// в очередь объектов домена для вставки, обновления или удаления,

// а второй подход перекладывает эту ответственность на сами объекты домена.

// UnitOfWork содержит обьект хранилища в памяти SplObjectStorage
// и обьект  AbstractDataMapper который  извлечекает объекты домена/сущности в базу данных и из нее.
// AbstractDataMapper получает сущность и с помощью PDO адаптера работает с базой данных

/**
 * Link: https://www.sitepoint.com/implementing-a-unit-of-work/
 *
 * Load data into database:
 *   `$php index.php --load-fixtures`
 *
 * Running example:
 *    `$php index.php`
 *
 * Show data in the database:
 *    `$sqlite`
 *    `sqlite> SELECT * FROM users;`
 *
 * @param $argv
 */
function main($argv){
    if ('--load-fixtures' === ($argv[1] ?? null)) {
        load();
    }else{
        example();
    }
}

function load(){
    $connection = new \PDO('sqlite:UOW.sqlite');

    $connection->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name VARCHAR(255), email VARCHAR(255), role VARCHAR(255))');

    $connection->exec('DELETE FROM users');
    $connection->exec('INSERT INTO users VALUES (1, "Petryk","petryk@yandex.ru","Guest")');
    $connection->exec('INSERT INTO users VALUES (2, "Kolya","kolya@gail.com","Administrator")');
    $connection->exec('INSERT INTO users VALUES (3, "Danila","danila@aol.com","Guest")');
    echo "Loaded\n";
}

function example(){

    $adapter = new App\Library\PdoAdapter('sqlite:UOW.sqlite');

    $unitOfWork = new App\ModelRepository\UnitOfWork(
        new App\Mapper\UserMapper($adapter, new App\ModelCollection\EntityCollection(),"users"),
        new App\LibraryStorage\ObjectStorage()
    );

        // фиксируем новые данные
        $user1 = new App\Model\User(array("name" => "NEW John Doe", "email" => "john@example.com"));
        $unitOfWork->registerNew($user1);

        // фиксируем данные для изменения
        $user2 = $unitOfWork->fetchById(1);
        $user2->name = "Joe";
        $unitOfWork->registerDirty($user2);

        // фиксируем данные для удаления
        $user3 = $unitOfWork->fetchById(2);
        if(!is_null($user3))$unitOfWork->registerDeleted($user3);

        // не фиксируем изменение
        $user4 = $unitOfWork->fetchById(3);
        $user4->name = "Julie";
        // или зафиксировать и снять фиксацию
        //$unitOfWork->registerDirty($user4);
        //$unitOfWork->registerClean($user4);

    $unitOfWork->commit();

}

main($argv);


