// Адаптер  позволяет объектам с несовместимыми интерфейсами работать вместе.
//Это объект-переводчик, который трансформирует интерфейс или данные одного объекта в такой вид, чтобы он стал понятен другому объекту.

class Banner {
    private text:string;
    constructor(text:string){this.text=text;}
    show_with_paren(){
        console.log("(",this.text,")");
    }
    show_with_aster(){
        console.info("*",this.text,"*");
    }
}

class PrintBanner {
    private banner:Banner;
    constructor(text:string){
        this.banner = new Banner(text);
    }
    print_weak(){
        this.banner.show_with_paren();
    }
    print_strong(){
        this.banner.show_with_aster();
    }
}

let p = new PrintBanner("Hello");
p.print_weak();
p.print_strong();

/*
(Hello)
*Hello*

*/
