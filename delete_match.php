<?php
require_once 'controllers/server.php';

if (isset($_GET['match_id'])) {
    $match_id = intval($_GET['match_id']); 
    
    try {
        
        $conn->beginTransaction();

        
        $stmt = $conn->prepare("DELETE FROM `match_value` WHERE match_id = :match_id");
        $stmt->bindParam(':match_id', $match_id);
        $stmt->execute();

     
        $stmt = $conn->prepare("DELETE FROM `detail` WHERE match_id = :match_id");
        $stmt->bindParam(':match_id', $match_id);
        $stmt->execute();

     
        $stmt = $conn->prepare("DELETE FROM `match` WHERE match_id = :match_id");
        $stmt->bindParam(':match_id', $match_id);
        $stmt->execute();

      
        $conn->commit();

        header("Location: dashboard_match.php?success=Deleted successfully!");
    } catch (PDOException $e) {
        $conn->rollback(); 
        header("Location: dashboard_match.php?error=Error deleting match. " . $e->getMessage());
    }
} else {
    header("Location: dashboard_match.php?error=Match ID not specified!");
}
?>
