class Deck{
    constructor(){
        this.deck = [1,2,3,4,5,6,7,8,9,10,"J","Q","K"];
        console.log("genero nuovo mazzo",this.deck);
    }
    shuffle(){
        this.deck.sort((a,b) => 0.5 - Math.random());
        console.log("mazzo mischiato", this.carte);
    }
    draw(){
        const card = this.deck.pop();
        console.log("carta pescata", card);
        console.log("mazzo dopo la pescata", this.deck);
        return card;
    }
    pushBack(card){
        this.deck.unshift(card);
        console.log("mazzo dopo averla rimessa sotto",this.deck);
    }
}
const deck = new Deck();
deck.shuffle();
card = deck.draw();
deck.pushBack(card);