<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['studentID'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

require 'connect.php';

$userId = $_SESSION['studentID'];

// Fetch CATs
$catQuery = $conn->prepare("
  SELECT GroupID, CategoryName, Score, OutOf
  FROM user_targets
  WHERE UserID = ?
");
$catQuery->bind_param("i", $userId);
$catQuery->execute();
$catResult = $catQuery->get_result();

$cats = [];
while ($row = $catResult->fetch_assoc()) {
  $groupId = $row['GroupID'];
  $category = $row['CategoryName'];
  $score = $row['Score'];
  $outOf = $row['OutOf'];
  $cats[$groupId][$category] = ['score' => $score, 'outOf' => $outOf];
}

// Fetch Exams
$examQuery = $conn->prepare("
  SELECT GroupID, Score, OutOf
  FROM user_exam_targets
  WHERE UserID = ?
");
$examQuery->bind_param("i", $userId);
$examQuery->execute();
$examResult = $examQuery->get_result();

$exams = [];
while ($row = $examResult->fetch_assoc()) {
  $exams[$row['GroupID']] = [
    'score' => $row['Score'],
    'outOf' => $row['OutOf']
  ];
}

// Fetch Group Names
$groupQuery = $conn->prepare("
  SELECT sg.GroupID, sg.Name
  FROM groupmemberships gm
  JOIN studygroups sg ON gm.GroupID = sg.GroupID
  WHERE gm.UserID = ?
");
$groupQuery->bind_param("i", $userId);
$groupQuery->execute();
$groupResult = $groupQuery->get_result();


$data = [];

while ($row = $groupResult->fetch_assoc()) {
  $groupId = $row['GroupID'];
  $unitName = $row['Name'];

  $catData = $cats[$groupId] ?? [];
  $examData = $exams[$groupId] ?? ['score' => null, 'outOf' => null];

  $catScores = [];
  for ($i = 1; $i <= 5; $i++) {
    $label = "CAT $i";
    if (isset($catData[$label])) {
      $score = $catData[$label]['score'];
      $outOf = $catData[$label]['outOf'];
      $catScores[] = $outOf > 0 ? round(($score / $outOf) * $outOf, 1) : 0;
    } else {
      $catScores[] = 0;
    }
  }

  $examScore = $examData['score'] ?? null;

  $totalCatOutOf = 0;
  foreach ($catData as $cat) {
    $totalCatOutOf += $cat['outOf'];
  }

  $examOutOf = $examData['outOf'] ?? 0;
  $totalOutOf = $totalCatOutOf + $examOutOf;


  $data[] = [
    'name' => $unitName,
    'cwWeight' => $totalCatOutOf,
    'examWeight' => $examOutOf,
    'catScores' => $catScores,
    'examScore' => $examScore
  ];
}

echo json_encode($data);
exit;
?>
