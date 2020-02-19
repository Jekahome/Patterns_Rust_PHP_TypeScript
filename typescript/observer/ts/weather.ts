// НАБЛЮДАТЕЛЬ
// Слабая связь(нет информации о друг друге) между субьектом и наблюдателями
// что дает легкость адаптации при изменении их
// При изменении субьекта происходит оповещение всех наблюдателей
//При использовании паттерна возможен как точечный запрос, так и
// рассылка (активная доставка) данных от субъекта (точечный запрос считается более «правильным»)

export interface Subject {
    registerObserver(obs:Observer): void;
    removeObserver(obs:Observer): void;
    notifyObservers(): void;
}
export interface GetData {
    getTemperature():number;
    getHumidity():number;
    getPressure():number;
}

export interface Observer {
    update(weatherData:GetData): void;
}

export interface DisplayElement {
    display(): void;
}

//Субьект
class WeatherData implements Subject,GetData{
    public observers: Array<Observer>;
    public temperature:number;
    public humidity:number;//влажность
    public pressure:number;//давление

    private changed:boolean;

    constructor(){
        this.observers = [];
        this.changed = false;
        this.temperature = 0;
        this.humidity = 0;
        this.pressure = 0;
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
    public registerObserver(obs:Observer): void{
        let index = this.observers.indexOf(obs);
        if(index < 0) {
            this.observers.push(obs);
        }
    }
    // Отмена регистрации наблюдателя
    public removeObserver(obs:Observer): void{
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
        this.setChanged();
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
class CurrentConditionsDisplay implements Observer, DisplayElement{
    private temperature:number;
    private humidity:number;//влажность
    private weatherData:Subject;// для отмены рассылки

    constructor(weatherData:Subject){
        this.weatherData = weatherData;
        this.temperature=0;
        this.humidity=0;
    }

    public update(weatherData:GetData ):void{
        // параметр с типом GetData для получения произвольных данных
        this.temperature = weatherData.getTemperature();
        this.humidity = weatherData.getHumidity();
        this.display();
    }

    public display(): void {
        console.log("Current conditions: ",this.temperature,"F degrees and ", this.humidity,"% humidity");
    }

    public removeObserver():void{
        this.weatherData.removeObserver(this);
    }
}


class StatisticsDisplay  implements Observer, DisplayElement{
    private maxTemperature:number;
    private minTemperature:number;
    private sumTemperature:number;
    private numReadings:number;
    private weatherData:Subject;

    constructor(weatherData:Subject){
        this.weatherData = weatherData;
        this.maxTemperature = 0.0;
        this.minTemperature = 200.0;
        this.sumTemperature = 0.0;
        this.numReadings = 0;
    }

    public update(weatherData:GetData):void{
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
        console.log("Avg/Max/Min temperature = " , (this.sumTemperature / this.numReadings)
            , "/" , this.maxTemperature , "/" , this.minTemperature);
    }
}

class ForecastDisplay  implements Observer, DisplayElement{
    private currentPressure:number;
    private lastPressure:number;
    private weatherData:Subject;

    constructor(weatherData:Subject){
        this.weatherData = weatherData;
        this.currentPressure = 29.92;
        this.lastPressure = this.currentPressure;
    }

    public update(weatherData:GetData):void{
        this.lastPressure = this.currentPressure;
        this.currentPressure = weatherData.getPressure();

        this.display();
    }

    public display(): void {
        console.log("Forecast: ");
        if (this.currentPressure > this.lastPressure) {
            console.log("Improving weather on the way!");
        } else if (this.currentPressure == this.lastPressure) {
            console.log("More of the same");
        } else if (this.currentPressure < this.lastPressure) {
            console.log("Watch out for cooler, rainy weather");
        }
    }
}



// рандомная загрузка данных
// json обьект изменения параметров от предыдущего оповещения, состояние?

let weatherData:WeatherData = new WeatherData();
let currentDisplay1:CurrentConditionsDisplay = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay1);

let currentDisplay2:CurrentConditionsDisplay = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay2);

let statisticsDisplay:StatisticsDisplay = new StatisticsDisplay(weatherData);
weatherData.registerObserver(statisticsDisplay);

let forecastDisplay:ForecastDisplay = new ForecastDisplay(weatherData);
weatherData.registerObserver(forecastDisplay);




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

loadData();
loadData();
currentDisplay1.removeObserver();