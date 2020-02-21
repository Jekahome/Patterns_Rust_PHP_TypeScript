// НАБЛЮДАТЕЛЬ
// Слабая связь(нет информации о друг друге) между субьектом и наблюдателями
// что дает легкость адаптации при изменении их
// При изменении субьекта происходит оповещение всех наблюдателей
//При использовании паттерна возможен как точечный запрос, так и
// рассылка (активная доставка) данных от субъекта (точечный запрос считается более «правильным»)

export interface SubjectImpl {
    registerObserver(obs:ObserverImpl): void;
    removeObserver(obs:ObserverImpl): void;
    notifyObservers(): void;
}
export interface GetDataImpl {
    getTemperature():number;
    getHumidity():number;
    getPressure():number;
}

export interface ObserverImpl {
    update(weatherData:GetDataImpl): void;
}

export interface DisplayElementImpl {
    display(): void;
}

//Субьект
class WeatherData implements SubjectImpl,GetDataImpl{
    public observers: Array<ObserverImpl>;
    public temperature:number;
    public humidity:number;//влажность
    public pressure:number;//давление

    private changed:boolean;

    constructor(){
        this.observers = [];
        this.temperature = 0;
        this.humidity = 0;
        this.pressure = 0;
        this.changed = false;
    }
    // Для оптимизации процесса оповещения
    private setChanged():void{
        this.changed = true;
    }
    private clearChanged():void{
        this.changed = false;
    }
    public hasChanged():boolean{
        return this.changed;
    }

    // Регистрация наблюдателя
    public registerObserver(obs:ObserverImpl): void{
        let index = this.observers.indexOf(obs);
        if(index < 0) {
            this.observers.push(obs);
        }
    }
    // Отмена регистрации наблюдателя
    public removeObserver(obs:ObserverImpl): void{
       let index = this.observers.indexOf(obs);
       if(index >=0){
           delete this.observers[index];
       }
    }
    // Оповещение наблюдателей об изменении состояния
    public notifyObservers(): void{
        if(this.hasChanged()){
            for (let index in this.observers) {
                if (this.observers[index]) {
                    this.observers[index].update(this);
                }
            }
            this.clearChanged();
        }
    }

    // Оповещение наблюдателей о появлении новых данных
    private measurementsChanged(): void{

        this.setChanged(); // Если новые данные не значительные можно не оповещать
        this.notifyObservers();
    }

    // Загрузка фейковых данных метеостанции с оповещением
    public setMeasurements( temperature:number, humidity:number, pressure:number): void{
        this.temperature = temperature;
        this.humidity = humidity;
        this.pressure = pressure;
        this.measurementsChanged();
    }

    // Реализация запроса состояния
    public getTemperature():number{
        return this.temperature;
    }
    public getHumidity():number{
        return this.humidity;
    }
    public getPressure():number{
        return this.pressure;
    }

}

// Наблюдатели
// Вывод информации
class CurrentConditionsDisplay implements ObserverImpl, DisplayElementImpl{
    private temperature:number;
    private humidity:number;//влажность
    private weatherData:SubjectImpl;// для отмены рассылки

    constructor(weatherData:SubjectImpl){
        this.weatherData = weatherData;
        this.temperature=0;
        this.humidity=0;
    }

    public update(weatherData:GetDataImpl ):void{
        // параметр с типом GetData для получения произвольных данных
        this.temperature = weatherData.getTemperature();
        this.humidity = weatherData.getHumidity();
        this.display();
    }

    public display(): void {
        console.log("Текущие условия: ");
        console.log(this.temperature,"F degrees and ", this.humidity,"% humidity");
    }

    public removeObserver():void{
        this.weatherData.removeObserver(this);
    }
}


class StatisticsDisplay  implements ObserverImpl, DisplayElementImpl{
    private maxTemperature:number;
    private minTemperature:number;
    private sumTemperature:number;
    private numReadings:number;
    private weatherData:SubjectImpl;

    constructor(weatherData:SubjectImpl){
        this.weatherData = weatherData;
        this.maxTemperature = 0.0;
        this.minTemperature = 200.0;
        this.sumTemperature = 0.0;
        this.numReadings = 0;
    }

    public update(weatherData:GetDataImpl):void{
        let tempTemperature:number = weatherData.getTemperature();
        this.sumTemperature += tempTemperature;
        this.numReadings++;
        if (tempTemperature > this.maxTemperature) {
            this.maxTemperature = tempTemperature;
        }

        if (tempTemperature < this.minTemperature) {
            this.minTemperature = tempTemperature;
        }

        this.display();
    }

    public display(): void {
        console.log("Статистика: ");
        console.log("Avg/Max/Min temperature = " , (this.sumTemperature / this.numReadings)
            , "/" , this.maxTemperature , "/" , this.minTemperature);
    }

    public removeObserver():void{
        this.weatherData.removeObserver(this);
    }
}

class ForecastDisplay  implements ObserverImpl, DisplayElementImpl{
    private currentPressure:number;
    private lastPressure:number;
    private weatherData:SubjectImpl;

    constructor(weatherData:SubjectImpl){
        this.weatherData = weatherData;
        this.currentPressure = 761;
        this.lastPressure = this.currentPressure;
    }

    public update(weatherData:GetDataImpl):void{
        this.lastPressure = this.currentPressure;
        this.currentPressure = weatherData.getPressure();

        this.display();
    }

    public display(): void {
        console.log("Прогноз: ");
        if (this.currentPressure > this.lastPressure) {
            console.log("Скоро будет улучшение погоды!");
        } else if (this.currentPressure == this.lastPressure) {
            console.log("Усиление погодных условий");
        } else if (this.currentPressure < this.lastPressure) {
            console.log("Остерегайтесь прохладной, дождливой погоды");
        }
    }

    public removeObserver():void{
        this.weatherData.removeObserver(this);
    }
}


let weatherData:WeatherData = new WeatherData();

let currentDisplay1:CurrentConditionsDisplay = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay1);

let currentDisplay2:CurrentConditionsDisplay = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay2);

let statisticsDisplay:StatisticsDisplay = new StatisticsDisplay(weatherData);
weatherData.registerObserver(statisticsDisplay);

let forecastDisplay:ForecastDisplay = new ForecastDisplay(weatherData);
weatherData.registerObserver(forecastDisplay);



// рандомная загрузка данных
function sleep(ms:number) {
    return new Promise(r => setTimeout(r, ms));
}
function getRandomTime(max:number) {
    return Math.floor(Math.random() * Math.floor(max)+1000);
}
function getRandomNumber(max:number) {
    return Math.floor(Math.random() * Math.floor(max)+1);
}

async function loadData() {
    console.log('Waiting...');
    await sleep(getRandomTime(4000));
    weatherData.setMeasurements(getRandomNumber(100),getRandomNumber(100),getRandomNumber(400)+400);
}
async function removeCurrentDisplay1() {
    console.log('remove...');
    await sleep(4000);
    currentDisplay1.removeObserver();
}
loadData();
loadData();
removeCurrentDisplay1();
/*
 Waiting...
 Waiting...

 Текущие условия:
 71 "F degrees and " 68 "% humidity"

 Текущие условия:
 39 "F degrees and " 27 "% humidity"

 Статистика:
 Avg/Max/Min temperature =  71 / 71 / 71

 Прогноз:
 Скоро будет улучшение погоды!

 Текущие условия:
 46 "F degrees and " 8 "% humidity"

 Статистика:
 Avg/Max/Min temperature =  58.5 / 71 / 46

 Прогноз:
 Остерегайтесь прохладной, дождливой погоды
 */
