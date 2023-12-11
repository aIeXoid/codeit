<?php 

class AddBidAction{

    public function __construct(private PDO $db) {
        //
    }
    
    public function addBid($data): void {
        
        $user_id = rand(1,1000); //get authorized user_id 

        //data from $_POST
        $bid = $data['bid']; 
        $auction_id = $data['auction_id'];

        try {
        
            //transaction to get current bid data and save new bid
            $this->db->beginTransaction();

            $currentBidStmt = $this->db->prepare("SELECT `bid` FROM `bid` WHERE `auction_id` = :auction_id ORDER BY `bid` DESC LIMIT 1");
            $currentBidStmt->bindParam(':auction_id', $auction_id, PDO::PARAM_INT);
            $currentBidStmt->execute();
            $currentBid = $currentBidStmt->fetchColumn();

            //Check for conditions
            if (($bid - $currentBid) < 1 && $bid <= $currentBid) {
                throw new Exception("The bet cannot be less than 1 dollar and less than the current one");
            }

            //Save new bid if all ok
            $newBidStmt = $this->db->prepare("INSERT INTO bid (auction_id, user_id, bid) VALUES (:auction_id, :user_id, :new_bid)");
            $newBidStmt->bindParam(':auction_id', $auction_id, PDO::PARAM_INT);
            $newBidStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $newBidStmt->bindParam(':new_bid', $bid, PDO::PARAM_STR);
            $newBidStmt->execute();

            
            $this->db->commit();
            
            echo "Bet successfully saved!";
        } catch (Exception $e) {
            //Rollback transaction if get an error
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>