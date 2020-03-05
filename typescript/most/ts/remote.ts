//Используйте паттерн Мост, если изменяться может не только реализация, но и абстракции.
//Отделить абстракцию от реализации так, чтобы и то и другое можно было изменять независимо
// как это независимо, если поменяется интерфейс Device то в классе Remote придется все переделывать, метода setVolume может не быть !

class Remote {
    protected device:Device;
    constructor(device:Device){
        this.device=device;
    }
    togglePower(){
        if (this.device.isEnable()){
            this.device.off();
        }else{
            this.device.on();
        }
    }
    volumeDown(){
        this.device.setVolume(this.device.getVolume()-10)
    }
    volumeUp(){
        this.device.setVolume(this.device.getVolume()+10)
    }
    channelDown(){
        this.device.setChanel( this.device.getChannel()-1);
    }
    channelUp(){
        this.device.setChanel( this.device.getChannel()+1);
    }
}

interface Device {
    isEnable():boolean;
    on();
    off();
    setChanel(channel:number);
    getChannel():number;
    getVolume():number;
    setVolume( volume:number);
}

class Tv implements Device{
    private enable:boolean;
    public channel:number;
    public prev_channel:number;
    private volume:number;

    constructor(){
        this.enable=false;
    }
    isEnable():boolean{return this.enable;}
    on(){this.enable=true;}
    off(){this.enable=false;}
    setChanel(channel:number){
        this.prev_channel = this.channel;
        this.channel=channel;
    }
    getChannel():number{
        return this.channel;
    }

    getVolume():number{return this.volume;}
    setVolume( volume:number){this.volume=volume;}
}

let tv = new Tv();
let romote = new Remote(tv);



console.log(tv.channel);