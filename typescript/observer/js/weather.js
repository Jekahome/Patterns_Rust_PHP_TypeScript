"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class WeatherData {
    constructor() {
        this.observers = [];
        this.changed = false;
        this.temperature = 0;
        this.humidity = 0;
        this.pressure = 0;
    }
    setChanged() {
        this.changed = true;
    }
    clearChanged() {
        this.changed = false;
    }
    hasChanged() {
        return this.changed;
    }
    registerObserver(obs) {
        let index = this.observers.indexOf(obs);
        if (index < 0) {
            this.observers.push(obs);
        }
    }
    removeObserver(obs) {
        let index = this.observers.indexOf(obs);
        if (index >= 0) {
            delete this.observers[index];
        }
    }
    notifyObservers() {
        if (this.hasChanged()) {
            for (let index in this.observers) {
                if (this.observers[index]) {
                    this.observers[index].update(this);
                }
            }
            this.clearChanged();
        }
    }
    measurementsChanged() {
        this.setChanged();
        this.notifyObservers();
    }
    setMeasurements(temperature, humidity, pressure) {
        this.temperature = temperature;
        this.humidity = humidity;
        this.pressure = pressure;
        this.measurementsChanged();
    }
    getTemperature() {
        return this.temperature;
    }
    getHumidity() {
        return this.humidity;
    }
    getPressure() {
        return this.pressure;
    }
}
class CurrentConditionsDisplay {
    constructor(weatherData) {
        this.weatherData = weatherData;
        this.temperature = 0;
        this.humidity = 0;
    }
    update(weatherData) {
        this.temperature = weatherData.getTemperature();
        this.humidity = weatherData.getHumidity();
        this.display();
    }
    display() {
        console.log("Current conditions: ", this.temperature, "F degrees and ", this.humidity, "% humidity");
    }
    removeObserver() {
        this.weatherData.removeObserver(this);
    }
}
class StatisticsDisplay {
    constructor(weatherData) {
        this.weatherData = weatherData;
        this.maxTemperature = 0.0;
        this.minTemperature = 200.0;
        this.sumTemperature = 0.0;
        this.numReadings = 0;
    }
    update(weatherData) {
        let tempTemperature = weatherData.getTemperature();
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
    display() {
        console.log("Avg/Max/Min temperature = ", (this.sumTemperature / this.numReadings), "/", this.maxTemperature, "/", this.minTemperature);
    }
}
class ForecastDisplay {
    constructor(weatherData) {
        this.weatherData = weatherData;
        this.currentPressure = 29.92;
        this.lastPressure = this.currentPressure;
    }
    update(weatherData) {
        this.lastPressure = this.currentPressure;
        this.currentPressure = weatherData.getPressure();
        this.display();
    }
    display() {
        console.log("Forecast: ");
        if (this.currentPressure > this.lastPressure) {
            console.log("Improving weather on the way!");
        }
        else if (this.currentPressure == this.lastPressure) {
            console.log("More of the same");
        }
        else if (this.currentPressure < this.lastPressure) {
            console.log("Watch out for cooler, rainy weather");
        }
    }
}
let weatherData = new WeatherData();
let currentDisplay1 = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay1);
let currentDisplay2 = new CurrentConditionsDisplay(weatherData);
weatherData.registerObserver(currentDisplay2);
let statisticsDisplay = new StatisticsDisplay(weatherData);
weatherData.registerObserver(statisticsDisplay);
let forecastDisplay = new ForecastDisplay(weatherData);
weatherData.registerObserver(forecastDisplay);
function sleep(ms) {
    return new Promise(r => setTimeout(r, ms));
}
function getRandomTime(max) {
    return Math.floor(Math.random() * Math.floor(max) + 1000);
}
function getRandomNumber(max) {
    return Math.floor(Math.random() * Math.floor(max) + 1);
}
async function loadData() {
    console.log('Waiting...');
    await sleep(getRandomTime(4000));
    weatherData.setMeasurements(getRandomNumber(100), getRandomNumber(100), getRandomNumber(400) + 400);
}
loadData();
loadData();
currentDisplay1.removeObserver();
