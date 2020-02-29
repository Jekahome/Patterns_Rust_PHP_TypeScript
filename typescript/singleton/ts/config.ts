class Config {
    private static instance: Config;
    private _login: string;
    private _password: string;
    private constructor() { }
    static getInstance() {
        if (!Config.instance) {
            Config.instance = new Config();
            Config.instance._login = "0000";
            Config.instance._password = "------";
        }
        return Config.instance;
    }
    get login(): string {
        return this._login;
    }
    set login(score) {
        this._login = score;
    }
    get password(): string {
        return this._password;
    }
    set password(score) {
        this._password = score;
    }

}

const myInstance = Config.getInstance();
myInstance.login = "login";
console.log(myInstance.login);
const myInstance2 = Config.getInstance();
console.log(myInstance.login);


