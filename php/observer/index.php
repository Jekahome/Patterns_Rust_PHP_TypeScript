<?php
declare(strict_types=1);

// интерфейсы поведения

 interface SubjectImpl
 {
  function registerObserver(ObserverImpl $obs): void;
  function removeObserver(ObserverImpl $obs): void;
  function notifyObservers(): void;
}

 interface GetDataImpl
 {
  function getTemperature():float;
  function getHumidity():float;
  function getPressure():int;
 }

 interface ObserverImpl
 {
  function update(GetDataImpl $weatherData): void;
 }

 interface DisplayElementImpl
 {
  function display(): void;
 }


//Субьект
class WeatherData implements SubjectImpl,GetDataImpl{
    public Array $observers = [];//ObserverImpl
    public float $temperature;
    public float $humidity;//влажность
    public int $pressure;//давление

    private bool $changed ;

    function __construct(){
        $this->observers = [];
        $this->changed = false;
        $this->temperature = 0.0;
        $this->humidity = 0.0;
        $this->pressure = 0;
    }
// Для оптимизации процесса оповещения
    private function setChanged():void{
        $this->changed = true;
    }
    private function clearChanged():void{
        $this->changed = false;
    }
    public function hasChanged():bool{
        return $this->changed;
    }

    // Регистрация наблюдателя
    public function registerObserver(ObserverImpl $obs): void{
        $index = array_search($obs,$this->observers,true);
        if($index == false) {
            array_push($this->observers,$obs);
        }
    }
    // Отмена регистрации наблюдателя
    public function removeObserver(ObserverImpl $obs): void{
        $index = array_search($obs,$this->observers,true);
       if($index >=0){
           unset($this->observers[$index]);
       }
    }
    // Оповещение наблюдателей об изменении состояния
    public function notifyObservers(): void{
        if($this->hasChanged()){
            foreach ($this->observers as $obs ) {
                if ($obs) {
                    $obs->update($this);
                }
            }
            $this->clearChanged();
        }
    }

    // Оповещение наблюдателей о появлении новых данных
    private function measurementsChanged(): void{
        $this->setChanged(); // Если новые данные не значительные можно не оповещать
        $this->notifyObservers();
    }

    // Загрузка фейковых данных метеостанции с оповещением
    public function setMeasurements( float $temperature, float $humidity, int $pressure): void{
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->pressure = $pressure;
        $this->measurementsChanged();
    }

    // Реализация запроса состояния
    public function getTemperature():float {
        return $this->temperature;
    }
    public function getHumidity():float{
        return $this->humidity;
    }
    public function getPressure():int{
        return $this->pressure;
    }

}


// Наблюдатели
// Вывод информации
class CurrentConditionsDisplay implements ObserverImpl, DisplayElementImpl{
    private float $temperature;
    private float $humidity;//влажность
    private SubjectImpl $weatherData;// для отмены рассылки

    function __construct(SubjectImpl $weatherData){
        $this->weatherData = $weatherData;
        $this->temperature = 0;
        $this->humidity = 0;
    }

    public function update(GetDataImpl $weatherData ):void{
        // параметр с типом GetData для получения произвольных данных
        $this->temperature = $weatherData->getTemperature();
        $this->humidity = $weatherData->getHumidity();
        $this->display();
    }

    public function display(): void {
        echo "\nТекущие условия: ";
        echo $this->temperature."F degrees and ". $this->humidity."% humidity\n";
    }

    public function removeObserver():void{
        $this->weatherData->removeObserver($this);
    }
}

class StatisticsDisplay  implements ObserverImpl, DisplayElementImpl{
    private float $maxTemperature;
    private float $minTemperature;
    private float $sumTemperature;
    private int $numReadings;
    private SubjectImpl $weatherData;

    function __construct(SubjectImpl $weatherData){
        $this->weatherData = $weatherData;
        $this->maxTemperature = 0.0;
        $this->minTemperature = 200.0;
        $this->sumTemperature = 0.0;
        $this->numReadings = 0;
    }

    public function update(GetDataImpl $weatherData):void{
        $tempTemperature = $weatherData->getTemperature();
        $this->sumTemperature += $tempTemperature;
        $this->numReadings++;
        if ($tempTemperature > $this->maxTemperature) {
            $this->maxTemperature = $tempTemperature;
        }

        if ($tempTemperature < $this->minTemperature) {
            $this->minTemperature = $tempTemperature;
        }

        $this->display();
    }

    public function display(): void {
        echo "\nСтатистика: ";
        echo "Avg/Max/Min temperature = " . ($this->sumTemperature / $this->numReadings).
        "/". $this->maxTemperature . "/" . $this->minTemperature."\n";
    }

    public function removeObserver():void{
        $this->weatherData->removeObserver($this);
    }
}

class ForecastDisplay  implements ObserverImpl, DisplayElementImpl{
    private int $currentPressure;
    private int $lastPressure;
    private SubjectImpl $weatherData;

    function __construct(SubjectImpl $weatherData){
        $this->weatherData = $weatherData;
        $this->currentPressure = 761;
        $this->lastPressure = $this->currentPressure;
    }

    public function update(GetDataImpl $weatherData):void{
        $this->lastPressure = $this->currentPressure;
        $this->currentPressure = $weatherData->getPressure();

        $this->display();
    }

    public function display(): void {
        echo "Прогноз: ";
        if ($this->currentPressure > $this->lastPressure) {
            echo "Скоро будет улучшение погоды!\n";
        } else if ($this->currentPressure == $this->lastPressure) {
            echo "Усиление погодных условий\n";
        } else if ($this->currentPressure < $this->lastPressure) {
            echo"Остерегайтесь прохладной, дождливой погоды\n";
        }
    }

    public function removeObserver():void{
        $this->weatherData->removeObserver($this);
    }
}


$weatherData = new WeatherData();

$currentDisplay1 = new CurrentConditionsDisplay($weatherData);
$weatherData->registerObserver($currentDisplay1);

$statisticsDisplay = new StatisticsDisplay($weatherData);
$weatherData->registerObserver($statisticsDisplay);

$forecastDisplay = new ForecastDisplay($weatherData);
$weatherData->registerObserver($forecastDisplay);

$currentDisplay1->removeObserver();
$weatherData->setMeasurements(74.0,52.0,761);

/*
 Статистика: Avg/Max/Min temperature = 74/74/74
 Прогноз: Усиление погодных условий
 */