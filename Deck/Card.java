//defines a Card class for the deck

public class Card{
    int value;
    String suit;
    
    Card(int value, String suit){
        this.value = value;
        this.suit = suit;
    }
    
    public int getValue(){
        return this.value;
    }
    
    public String getSuit(){
        return this.suit;
    }
}