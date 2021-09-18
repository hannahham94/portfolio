public class TestDeck {
  public static void main(String[] args) {
        Deck myDeck = new Deck();
        Deck myDeck2 = new Deck();
        //myDeck.printDeck();
        myDeck.shuffle();
        myDeck2.shuffle();
        //System.out.println("");
        //myDeck.printDeck();
        myDeck.addToHand(5);
        myDeck2.addToHand(5);
        myDeck.printHand();
        System.out.println("");
        myDeck2.printHand();
        System.out.println("");
        //myDeck.printDrawnCards();
        System.out.println(myDeck.getHandType());
        System.out.println(myDeck2.getHandType());
        System.out.println(myDeck.winningHand(myDeck2));
    }
}