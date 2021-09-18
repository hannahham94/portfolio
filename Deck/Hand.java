/**************************
a class for a hand of cards
Note: This class includes methods to check a poker hand
***************************/
import java.util.*;

public class Hand{
    List<Card> hand = new ArrayList<Card>();
    int handSize;
    int cardLimit;
    //track highest double value
    int doubleValue;
    int tripleValue;
    int fourValue;
    int highest;
    
    //initialize a hand with 0s, sets card limit to a high number
    Hand(){
        this.handSize = 0;
        this.cardLimit = 1000;
        this.doubleValue = 0;
        this.tripleValue = 0;
        this.fourValue = 0;
        this.highest = 0;
    }
    
    //method to print the hand
    public void printHand(){
        for(int i=0; i<hand.size(); i++){
            int value = hand.get(i).getValue();
            String suit = hand.get(i).getSuit();
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
    
    //adds a card to the hand if the card limit for the hand isn't reached
    public void addCard(Card card){
        if(this.handSize + 1 <= this.cardLimit){
            this.hand.add(card);
            this.handSize += 1;
        }
    }
    
    //returns the size of the hand
    public int getHandSize(){
        return this.handSize;
    }
    
    //sets a card limit for the hand
    public void setCardLimit(int num){
        this.cardLimit = num;
    }
    
    //high card method for draws
    //also applies for flushes
    public int getHighCard(){
        int highValue = 0;
        for(int i = 0; i<hand.size(); i++){
            if(hand.get(i).getValue() > highValue){
                highValue = hand.get(i).getValue();
            }
        }
        return highValue;
    }
    
    //returns high pair value
    public int getPair(){
        return this.doubleValue;
    }
    
    //returns the value of the three of a kind
    public int getTriple(){
        return this.tripleValue;
    }
    
    //returns the value of the four of a kind
    public int getFour(){
        return this.fourValue;
    }
    
    //method for a poker hand, currently works with aces low, with more time I would change to aces high
    public String pokerHand() {
        int[] countSuits = {0, 0, 0, 0};
        int[] countValues = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
        for(int i = 0; i<hand.size();i++){
            int value = hand.get(i).getValue();
            String suit = hand.get(i).getSuit();
            //search for suits
            if(suit == "hearts"){
                countSuits[0] += 1;
            }
            else if(suit == "spades"){
                countSuits[1] += 1;
            }
            else if(suit == "clubs"){
                countSuits[2] += 1;
            }
            else if(suit == "diamonds"){
                countSuits[3] += 1;
            }
          
            //search for duplicates
            switch(value){
                case 1: countValues[0] += 1; break;
                case 2: countValues[1] += 1; break;
                case 3: countValues[2] += 1; break;
                case 4: countValues[3] += 1; break;
                case 5: countValues[4] += 1; break;
                case 6: countValues[5] += 1; break;
                case 7: countValues[6] += 1; break;
                case 8: countValues[7] += 1; break;
                case 9: countValues[8] += 1; break;
                case 10: countValues[9] += 1; break;
                case 11: countValues[10] += 1; break;
                case 12: countValues[11] += 1; break;
                case 13: countValues[12] += 1; break;
            }
        }
      
        //boolean value to check for a straight for straight flush and regular straight
        boolean hasStraight = this.checkStraight(countValues);
      
        //check for flush
        for(int i=0; i<countSuits.length;i++){
            if(countSuits[i] == 5){
                //royal flush
                if(countValues[0] == 1 && countValues[9] == 1 && countValues[10] == 1 && countValues[11] == 1 && countValues[12] == 1){
                    return "Royal Flush";
                }
                //straight flush
                else if(hasStraight){
                    return "Straight Flush";
                }
                //regular flush
                else{
                    return "Flush";
                }
            }
        }
        if(hasStraight){
            return "Straight";
        }
        //check for multiples
        boolean hasTriple = false;
        int hasDouble = 0;
        for(int i=0; i<countValues.length; i++){
            if(countValues[i] == 4){
                this.fourValue = i;
                return "Four of a Kind";
            }
            else if(countValues[i] == 3){
                hasTriple = true;
                this.tripleValue = i;
            }
            else if(countValues[i] == 2){
                this.doubleValue = i;
                hasDouble += 1;
            }
        }
        //check full house and pairs
        if(hasTriple && hasDouble > 0){
            return "Full House";
        }
        else if(hasTriple){
            return "Three of a Kind";
        }
        else if(hasDouble > 0){
            if(hasDouble == 2){
                return "Two Pair";
            }
            else if(hasDouble ==1){
                return "Pair";
            }
        }
        return "high card";
      
    }
    
    //method to check for a straight
    public boolean checkStraight(int[] values){
        int count = 0;
        for(int i=0; i<values.length;i++){
            if(values[i] == 1){
                count += 1;
            }
            else{
                count = 0;
            }
            if(count == 5){
                return true;
            }
        }
        return false;
    }
}