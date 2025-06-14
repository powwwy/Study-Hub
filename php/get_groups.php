<?php
require 'connect.php';

$sql = "SELECT groupID, name, category, description FROM studygroups";
$result = $conn->query($sql);

$groups = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($groups);
?>
