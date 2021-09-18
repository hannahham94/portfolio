//defines a class for a deck of cards
import java.util.*;

public class Deck{
    List<Card> drawnCards = new ArrayList<Card>();
    Hand hand = new Hand();
    //string for what type of hand for poker game
    String handType;
    Card[] inDeck = new Card[52];
    int cardsCount;
    //keeps a regular deck stored in case you want to start a new game without creating a new Deck object
    //used in resetDeck() function
    Card[] initDeck = new Card[52];
    
    //initialize a standard deck with 4 of each type of card
    Deck(){
        int tracker = 1;
        String[] suit = {"hearts", "spades", "clubs", "diamonds"};
        int suitChooser = 0;
        for(int i=0; i<inDeck.length; i++) {
            if (tracker == 14) {
                tracker = 1;
                suitChooser += 1;
            }
            initDeck[i] = new Card(tracker, suit[suitChooser]);
            inDeck[i] = new Card(tracker, suit[suitChooser]);
            tracker += 1;
        }
        this.cardsCount = 52;
        this.handType = "None";
    }
    
    //this method will shuffle a deck
    public void shuffle(){
        //implement a shuffle of inDeck
        Random rand = new Random();
        for(int i = 0; i < inDeck.length; i++){
          int swapper = rand.nextInt(inDeck.length);
          Card temp = inDeck[swapper];
          inDeck[swapper] = inDeck[i];
          inDeck[i] = temp;
        }
    }
    
    //this method will print a deck
    public void printDeck(){
        for(int i=0; i<inDeck.length; i++){
            int value = inDeck[i].getValue();
            String suit = inDeck[i].getSuit();
            if(value == 11){
                System.out.println("Jack of " + suit);
            }
            else if(value == 12){
                System.out.println("Queen of " + suit);
            }
            else if(value == 13){
                System.out.println("King of " + suit);
            }
            else if(value == 1){
                System.out.println("Ace of " + suit);
            }
            else{
                System.out.println(value + " of " + suit);
            }
        }
    }
    
    //function to reset a deck
    public void resetDeck(){
        for(int i=0; i<52; i++) {
            inDeck[i] = initDeck[i];
        }
    }
    
    //this method will remove a card from the deck and return it
    public Card drawCard(){
        Card topCard = inDeck[cardsCount-1];
        this.drawnCards.add(topCard);
        inDeck[cardsCount-1] = null;
        cardsCount--;
        return topCard;
    }
    
    //this method will print the cards drawn from the deck so far
    public void printDrawnCards(){
       for(int i=0; i<drawnCards.size(); i++){
            int value = drawnCards.get(i).getValue();
            String suit = drawnCards.get(i).getSuit();
            if(value == 11){
                System.out.println("Jack of " + suit);
            }
            else if(value == 12){
                System.out.println("Queen of " + suit);
            }
            else if(value == 13){
                System.out.println("King of " + suit);
            }
            else if(value == 1){
                System.out.println("Ace of " + suit);
            }
            else{
                System.out.println(value + " of " + suit);
            }
        }
    }
    
    //this method draws a number of cards and adds them to the hand
    public void addToHand(int cardAmt){
        for(int i=0; i<cardAmt; i++) {
            hand.addCard(this.drawCard());
        }
    }
    
    //call the hands print hand method, to print this decks hand
    public void printHand(){
        this.hand.printHand();
    }
    
    //you can set the hand size. In a poker match, you would set it to five, blackjack 2, etc
    public void setHandSize(int limit) {
        this.hand.setCardLimit(limit);
    }
    
    //set the hand type
    public void checkHand(){
        this.handType = this.hand.pokerHand();
    }
    
    //returns the hand type after checking the hand
    public String getHandType(){
        this.checkHand();
        return this.handType;
    }
    
    //returns this deck's hand
    public Hand getHand(){
        return this.hand;
    }
    
    /*this was an initial idea and I'm not super happy with the length of this once I implemented it
    with more time, I would change this to a number system instead of using strings. I could easily
    condense the code with an integer comparison*/
    public String winningHand(Deck opponent){
        Hand oppHand = opponent.getHand();
        if(this.getHandType() == opponent.getHandType()){
            //if these types draw, check for the high card to see a winner
            if(this.handType == "high card" || this.handType == "Straight" || this.handType == "Flush" || this.handType == "Full House" || this.handType == "Straight Flush") {
                if(this.hand.getHighCard() > oppHand.getHighCard()){
                    return "winner";
                }
                else if(this.hand.getHighCard() == oppHand.getHighCard()){
                    return "draw";
                }
                else{return "loser";}
            }
            //if pairs draw, check highest pair
            else if(this.handType == "Pair" || this.handType == "Two Pair"){
                if(this.hand.getPair() > oppHand.getPair()){
                    return "winner";
                }
                else if(this.hand.getPair() == oppHand.getPair()){
                    return "draw";
                }
                else{return "loser";}
            }
            //if triples draw, check highest triple
            else if(this.handType == "Three of a Kind"){
                    if(this.hand.getTriple() > oppHand.getTriple()){
                        return "winner";
                    }
                    else if(this.hand.getTriple() == oppHand.getTriple()){
                        return "draw";
                    }
                    else{return "loser";}
            }
            //if four of a kind draw, check highest four of a kind
            else if(this.handType == "Four of a Kind"){
                if(this.hand.getFour() > oppHand.getFour()){
                    return "winner";
                }
                else if(this.hand.getFour() == oppHand.getFour()){
                    return "draw";
                }
                else{return "loser";}
            }
            else{
                return "draw";
            }
        }
        //finally, check which hand wins if not a draw
        else if(this.getHandType() == "Royal Flush"){
            return "winner";
        }
        else if(opponent.getHandType() == "Royal Flush"){
            return "loser";
        }
        else if(this.getHandType() == "Straight Flush"){
            return "winner";
        }
        else if(opponent.getHandType() == "Straight Flush"){
            return "loser";
        }
        else if(this.getHandType() == "Four of a Kind"){
            return "winner";
        }
        else if(opponent.getHandType() == "Four of a Kind"){
            return "loser";
        }
        else if(this.getHandType() == "Full House"){
            return "winner";
        }
        else if(opponent.getHandType() == "Full House"){
            return "loser";
        }
        else if(this.getHandType() == "Flush"){
            return "winner";
        }
        else if(opponent.getHandType() == "Flush"){
            return "loser";
        }
        else if(this.getHandType() == "Straight"){
            return "winner";
        }
        else if(opponent.getHandType() == "Straight"){
            return "loser";
        }
                else if(this.getHandType() == "Three of a Kind"){
            return "winner";
        }
        else if(opponent.getHandType() == "Three of a Kind"){
            return "loser";
        }
        else if(this.getHandType() == "Two Pair"){
            return "winner";
        }
        else if(opponent.getHandType() == "Two Pair"){
            return "loser";
        }
        else if(this.getHandType() == "Pair"){
            return "winner";
        }
        else if(opponent.getHandType() == "Pair"){
            return "loser";
        }
        else{
            return "error";
        }
    }
}